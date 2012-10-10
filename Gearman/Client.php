<?php
namespace Uecode\GearmanBundle\Gearman;
use GearmanClient;

/**
 * @author Aaron Scherer
 * @date 10/10/12
 */

class Client extends GearmanClient
{
	/**
	 * @var Connection[]
	 */
	private $servers = array();

	public function __construct( $configs )
	{
		$this->addConnections( $configs[ 'connections' ] );
	}

	/**
	 * @param array $connections
	 */
	private function addConnections( array $connections )
	{
		foreach( $connections as $name => $c )
		{
			$connection = new Connection( $name, $c[ 'host' ], $c[ 'port' ] );
			$this->servers[ $name ] = $this->addConnection( $connection );
		}
	}

	/**
	 * @param Connection $connection
	 * @return Connection
	 */
	private function addConnection( Connection $connection )
	{
		$conn = $this->addServer( $connection->getHost(), $connection->getPort() );
		if( $conn )
		{
			$connection->setConnected( true );
			$connection->setConnectionError( 'NONE' );
		}
		else
		{
			$connection->setConnected( false );
			$connection->setConnectionError( $this->error() );
		}

		return $connection;
	}
}
