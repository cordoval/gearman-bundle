<?php
namespace Uecode\GearmanBundle\Gearman;

/**
 * @author Aaron Scherer
 * @date 10/10/12
 */
class Connection
{
	/**
	 * Server Name
	 * @var string
	 */
	private $name = '';

	/**
	 * Server Host Name / IP
	 * @var string
	 */
	private $host = '127.0.0.1';
	/**
	 * Server Port Number
	 * @var int
	 */
	private $port = 4370;

	/**
	 * Connected Status
	 * @var bool
	 */
	private $connected = false;

	/**
	 * If Connection failed, Error Reason
	 * @var string
	 */
	private $connectionError = 'NO ERROR';

	public function __construct( $name, $hostname, $port )
	{
		$this->setName( $name );
		$this->setHost( $hostname );
		$this->setPort( $port );
	}

	/**
	 * @param string $name
	 */
	private function setName( $name )
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $connectionError
	 */
	public function setConnectionError( $connectionError )
	{
		$this->connectionError = $connectionError;
	}

	/**
	 * @return string
	 */
	public function getConnectionError()
	{
		return $this->connectionError;
	}

	/**
	 * @param boolean $connected
	 */
	public function setConnected( $connected )
	{
		$this->connected = $connected;
	}

	/**
	 * @return boolean
	 */
	public function getConnected()
	{
		return $this->connected;
	}

	/**
	 * @param string $host
	 */
	private function setHost( $host )
	{
		$this->host = $host;
	}

	/**
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @param int $port
	 */
	private function setPort( $port )
	{
		$this->port = $port;
	}

	/**
	 * @return int
	 */
	public function getPort()
	{
		return $this->port;
	}
}
