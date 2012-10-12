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
			->setConfigs( $container->getParameter( 'uecode.gearman' ) )
			->setGearmanClient( new Client( $this->getConfigs() ) );
	}

	public function createJob( $name, $payload, $priority = 'Normal', $block = true )
	{
		$job = new Job( $name, $payload );
		$job->setClient( $this->getGearmanClient() );
		$job->setPriority( $priority );
		$job->setBlock( $block );

		$result = $job->execute();

		$this->jobs[ $name ] = $job;
		return $result;
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
