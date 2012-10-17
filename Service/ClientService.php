<?php
/**
 * @author Aaron Scherer
 * @date 10/8/12
 */
namespace Uecode\GearmanBundle\Service;
use Symfony\Component\DependencyInjection\Container;

// Gearman Classes
use Uecode\GearmanBundle\Gearman\Client;
use Uecode\GearmanBundle\Gearman\Job;

class ClientService
{

	/**
	 * @var Container;
	 */
	private $container;

	/**
	 * @var array
	 */
	private $configs;

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var Job[]
	 */
	private $jobs = array();

	/**
	 * @param \Symfony\Component\DependencyInjection\Container $container
	 */
	public function init( Container $container )
	{
		$this
			->setContainer( $container )
			->setConfigs( $container->getParameter( 'uecode.gearman.client' ) )
			->setGearmanClient( new Client( $this->getConfigs() ) );
	}

	public function createJob( $name, $payload, $priority = 'Normal', $block = true )
	{
		$job = new Job( $name, $payload );
		$job->setClient( $this->getGearmanClient() );
		$job->setPriority( $priority );
		$job->setBlock( $block );

		$i = 0;
		do
		{
			if( $i >= $this->getConfig( 'attempts' ) )
				break;
			$job->execute();
			$i++;
			sleep( 1 );
		}
		while( $this->getGearmanClient()->getReturnCode() !== GEARMAN_SUCCESS );
		$error = array( 'attempts' => $i, 'return_code' => $this->getGearmanClient()->getReturnCode() );

		if( $this->getGearmanClient()->getReturnCode() !== GEARMAN_SUCCESS )
		{
			$error = array_merge( $error, $this->getGearmanClient()->getError() );
		}
		$job->setError( $error );
		$this->jobs[ $name ] = $job;
		return $job;
	}

	public function createTask( $name, $payload, $priority = 'Normal', $block = true )
	{

	}

	public function runTasks( )
	{
		$this->getGearmanClient()->runTasks();
	}

	public function checkJob( Job $job )
	{
		$handle = $job->getBlock() ? $job->getHash() : $job->getResult();
		echo "\$this->getGearmanClient()->jobStatus( $handle );\n";
		return $this->getGearmanClient()->jobStatus( $handle );
	}

	public function getConfig( $key )
	{
		if( !array_key_exists( $key, $this->configs ) )
			throw new \Exception( sprintf( 'The `%s` key does not exist in the configs.', $key ) );
		return $this->configs[ $key ];
	}

	/**
	 * @return array
	 */
	public function getConfigs()
	{
		return $this->configs;
	}

	/**
	 * @return Client
	 */
	public function getGearmanClient( )
	{
		return $this->client;
	}

	/**
	 * @param Client $client
	 * @return ClientService
	 */
	protected function setGearmanClient( Client $client )
	{
		$this->client = $client;
		return $this;
	}

	/**
	 * @param array $configs
	 * @return ClientService
	 */
	protected function setConfigs( array $configs )
	{
		$this->configs = $configs;
		return $this;
	}

	/**
	 * @param \Symfony\Component\DependencyInjection\Container $container
	 * @return ClientService
	 */
	protected function setContainer( Container $container )
	{
		$this->container = $container;
		return $this;
	}

	/**
	 * @return \Symfony\Component\DependencyInjection\Container
	 */
	protected function getContainer( )
	{
		return $this->container;
	}
}
