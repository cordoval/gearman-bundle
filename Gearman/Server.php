<?php
/**
 * @author Aaron Scherer ( aaron@undergroundelephant.com )
 * @date 2012
 * @copyright Underground Elephant
 */
namespace Uecode\GearmanBundle\Gearman;

// Uecode Classes
use Uecode\CommonBundle\Config;
use Uecode\CommonBundle\Log;
use Uecode\GearmanBundle\Gearman\Connection;

// Symfony Classes
use \Symfony\Component\Process\Process;
use \Symfony\Component\Process\ProcessBuilder;

/**
 *
 */
class Server
{
	/**
	 * Configs
	 * @var Config
	 */
	private $_config;

	/**
	 * Gearman Connection
	 * @var Connection
	 */
	private $_connection;

	/**
	 * Logger
	 * @var Log
	 */
	private $_log;

	/**
	 * @var
	 */
	private $_pid;

	/**
	 * @var
	 */
	private $_queue;

	/**
	 * @var
	 */
	private $_queueConfig;

	/**
	 * @param $configs
	 */
	public function __construct( $configs )
	{
		$this->_config = new Config( $configs );
		$this->initialize();
	}

	/**
	 *
	 */
	private function initialize()
	{
		$this->_connection = new Connection(
			$this->_config->get( 'host' ),
			$this->_config->get( 'port' )
		);
		$this->setLog( $this->_config->get( 'log_dir' ) );
		$this->setPidFile( $this->_config->get( 'pid_dir' ) );
	}

	/**
	 * @param $directory
	 */
	private function setPidFile( $directory )
	{
		$file = rtrim( $directory, '/' ) . '/gearmnServer.pid';
		if( file_exists( $file ) )
		{
			$pid = file_get_contents( $file );
			if( !$this->checkRunning( $pid ) )
			{
				unlink( $file );
				$this->_log->log( 'Orphaned PID file. Destroying. ' );
			}
		}
		else
		{
			if( !$this->checkRunning( false ) )
			{
				if( !is_dir( $directory ) )
				{
					$process = new Process( 'mkdir -p ' . $directory );
					$process->run();
				}
			}
		}
	}

	/**
	 * @param $pid
	 * @return bool
	 */
	private function checkRunning( $pid )
	{
		if( $pid !== false )
		{
			$process = new Process( "ps -p {$pid} | wc -l" );
			$processes = (int) $process->getOutput() - 1;
			if( $processes <= 1 )
			{
				return false;
			}
		}
		else
		{
			$cmd = $this->buildCommand();
			$process = new ProcessBuilder( array( 'ps', '-ef', '|', 'grep' ) );
			$process->add( "\"$cmd\"" );
			$process->getProcess()->run();
			$processes = (int) $process->getOutput() - 1;
			if( $processes <= 1 )
			{
				return false;
			}
		}
		return true;
	}

	/**
	 * @param bool $test
	 * @return string
	 */
	private function buildCommand( $test = false )
	{
		$pid = rtrim( $this->_config->get( 'pid_dir' ), '/' ) . '/gearmnServer.pid';

		$process = new ProcessBuilder( 'gearmand' );

		if( !$test ) $process->add( '--daemon' );

		$process->add( '--log-file=' . $this->_log->getFile() )
			->add( '--listen=' . $this->_config->get( 'host' ) )
			->add( '--port=' . $this->_config->get( 'port' ) )
			->add( '--pid-file=' . $pid ) );

		$queueType = $this->_config->get( 'queue_type' );

		if( !empty( $queueType ) )
		{
			$process->add( '--queue=' . $this->_config->get( 'queue_type' ) );
			switch( $queueType )
			{
				case 'libsqlite3':
					$db = rtrim( $this->_config->get( 'db_dir' ), '/' ) . '/queue.db';
					$process->add( '--libsqlite3-db=' . $db );
					break;
			}
		}
		return $process->getProcess()->getCommandLine();
	}

	/**
	 * @param $directory
	 * @return Server
	 */
	private function setLog( $directory )
	{
		$this->_log = new Log( 'gearman_server', rtrim( $directory, '/' ) . '/gearmanServer.log' );
		return $this;
	}
}
