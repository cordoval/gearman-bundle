<?php
namespace Uecode\GearmanBundle\Gearman;

/**
 * @author Aaron Scherer
 * @date 10/10/12
 */

class Job
{
	/**
	 * Function Name
	 * @var string
	 */
	private $name = '';

	/**
	 * Job Payload
	 * @var mixed
	 */
	private $payload;

	/**
	 * Job Priority
	 * @var string
	 */
	private $priority = 'Normal';

	/**
	 * Toggles Backrounding
	 * @var bool
	 */
	private $block = true;

	/**
	 * Unique Job Identifier
	 * @var string
	 */
	private $hash = '';

	/**
	 * Gearman\Client
	 * @var Client
	 */
	private $client;

	private static $PRIORITIES = array( 'Normal', 'High', 'Low' );

	public function __construct( $name, $payload )
	{
		$this->setName( $name );
		$this->setPayload( $payload );
		$this->createHash();
	}

	public function execute()
	{
		$method = 'do';
		if( $this->getBlock() )
		{
			$method .= ( $this->getPriority() == 'Normal' ? '' : $this->getPriority() ) . 'Background';
		}
		else
		{
			$method .= $this->getPriority();
		}
		return $this->getClient()->$method( $this->getName(), $this->getPayload(), $this->getHash() );
	}

	/**
	 * @param string $hash
	 */
	public function setHash( $hash )
	{
		$this->hash = $hash;
	}

	/**
	 * @return string
	 */
	public function getHash()
	{
		return $this->hash;
	}

	private function createHash( )
	{
		$key = $this->getName() . json_encode( $this->getPayload() );
		for( $i = 0; $i < 10; $i++ )
		{
			$key = sha1( $key );
		}
		$this->setHash( $key );
	}

	/**
	 * @param boolean $block
	 */
	public function setBlock( $block )
	{
		$this->block = $block;
	}

	/**
	 * @return boolean
	 */
	public function getBlock()
	{
		return $this->block;
	}

	/**
	 * @param $priority
	 * @throws \Exception On invalid Priority
	 */
	public function setPriority( $priority )
	{
		$priority = ucwords( $priority );
		if( !in_array( $priority, self::$PRIORITIES ) )
		{
			throw new \Exception( sprintf( "`%s` is not a valid priority. Please use a valid priority (%s).", $priority, self::$PRIORITIES ) );
		}
		$this->priority = $priority;
	}

	/**
	 * @return string
	 */
	public function getPriority()
	{
		return $this->priority;
	}

	/**
	 * @param \Uecode\GearmanBundle\Gearman\Client $client
	 */
	public function setClient( $client )
	{
		$this->client = $client;
	}

	/**
	 * @return \Uecode\GearmanBundle\Gearman\Client
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name )
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
	 * @param mixed $payload
	 */
	public function setPayload( $payload )
	{
		$this->payload = $payload;
	}

	/**
	 * @return mixed
	 */
	public function getPayload()
	{
		return $this->payload;
	}

}
