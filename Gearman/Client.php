<?php
namespace Uecode\GearmanBundle\Gearman;

use \GearmanClient;
use \Uecode\Daemon\Exception;

/**
 * @author Aaron Scherer
 * @date 10/10/12
 */

class Client
{
	/**
	 * @var Connection[]
	 */
	private $_servers = array();

	/**
	 * @var GearmanClient
	 */
	private $_client;

	/**
	 * Timeout for Client
	 * @var int
	 */
	private $_timeout;

	public function __construct( $configs )
	{
		$this->initializeClient( $configs );
	}

	private function initializeClient( $configs )
	{
		$this->_client = new \GearmanClient();
		$this->_timeout = $configs[ 'timeout' ];
		$this->addConnections( $configs[ 'connections' ] );
	}

	/**
	 * @return int
	 */
	public function getTimeout()
	{
		return $this->_timeout;
	}

	/**
	 * @return \GearmanClient
	 */
	public function getClient()
	{
		return $this->_client;
	}

	public function getServers()
	{
		return $this->_servers;
	}

	public function addServer( $name, Connection $conn )
	{
		$this->_servers[ $name ] = $conn;

		return $this;
	}

	/**
	 * @param array $connections
	 */
	private function addConnections( array $connections )
	{
		foreach ( $connections as $name => $c ) {
			$this->addServer( $name, new Connection( $name, $c[ 'host' ], $c[ 'port' ] ) );
		}
	}

	private function connect( )
	{
		$connected = false;
		foreach( $this->_servers as &$server )
		{
			$conn = $this->_client->addServer( $server->getHost(), $server->getPort() );
			if ( $conn )
			{
				$server->setConnected( true );
				$server->setConnectionError( 'NONE' );
				$connected = true;
			}
			else
			{
				$server->setConnected( false );
				$server->setConnectionError( $this->error() );
			}
		}
		return $connected;
	}

	private function disconnect( )
	{

		foreach( $this->_servers as &$server )
		{
			$server->setConnected( false );
			$server->setConnectionError( 'NO ERROR' );
		}
	}

	public function getReturnCode()
	{
		return $this->_client->returnCode();
	}

	public function getError()
	{
		return array( 'error' => $this->_client->error(), 'error_no' => $this->_client->getErrno() );
	}

	public function performAction( $type, $name, $payload, $hash, $priority = 'Normal', $block = true )
	{
		$priority = ucwords( strtolower( $priority ) );
		if ( !in_array( $priority, array( 'Low', 'Normal', 'High' ) ) )
		{
			throw new Exception( "Priority must be 'Low', 'Normal', or 'High'!" );
		}

		$method = '';
		if ( $type == 'job' ) {
			$method = 'do';
			if ( !$block && $priority == 'Normal' )
			{
				$priority = '';
			}
			$method .= $priority . ( $block ? '' : 'Background' );
		} else {
			$method = 'addTask' . ( $priority != 'Normal' ? $priority : '' ) . ( $block ? '' : 'Background' );
		}
		echo "Method: $method \t Worker: {$name} \t Payload: {$payload} \t Hash: {$hash}\n";

		if( $this->connect() )
		{
			$res = $this->_client->{$method}( $name, $payload, $hash );
			$this->disconnect();
			return $res;
		}
		throw new Exception( 'Could not connect to the gearman servers.' );
	}

	public function runTasks()
	{
		$this->_client->runTasks();
	}

	public function __call( $method, $args )
	{
		if ( !method_exists( $this, $method ) ) {
			trigger_error(
				sprintf( "`%s` has moved. Please use `%s`", $method, "->_client->{$method}()" ),
				E_NOTICE
			);
			call_user_func_array( array( 'this', $method ), $args );
		}
	}
}
