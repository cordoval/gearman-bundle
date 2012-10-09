<?php
/**
 * @author Aaron Scherer
 * @date 10/8/12
 */
namespace Uecode\GearmanBundle;
use Symfony\Component\DependencyInjection\Container;

// Gearman Classes
use GearmanClient;
use GearmanException;
use GearmanJob;
use GearmanTask;


class Client
{

	/**
	 * @var Container;
	 */
	protected $container;

	/**
	 * @var array
	 */
	protected $configs;

	/**
	 * @var GearmanClient
	 */
	protected $client;


	/**
	 * @param \Symfony\Component\DependencyInjection\Container $container
	 */
	public function init( Container $container )
	{
		$this
			->setContainer( $container )
			->setConfigs( $container->getParameter( 'uecode.gearman' ) )
			->setGearmanClient( new GearmanClient() )
			->initializeConnections();
	}

	/**
	 *
	 */
	public function initializeConnections()
	{
		foreach( $this->configs[ 'connections' ] as $server )
		{
				$this->client->addServer( $server[ 'host' ], $server[ 'port' ] );
		}
	}




	/**
	 * @return array
	 */
	public function getConfigs()
	{
		return $this->configs;
	}

	/**
	 * @return \GearmanClient
	 */
	public function getGearmanClient( )
	{
		return $this->client;
	}




	/**
	 * @param \GearmanClient $client
	 * @return Client
	 */
	protected function setGearmanClient( GearmanClient $client )
	{
		$this->client = $client;
		return $this;
	}
	/**
	 * @param array $configs
	 * @return Client
	 */
	protected function setConfigs( array $configs )
	{
		$this->configs = $configs;
		return $this;
	}

	/**
	 * @param \Symfony\Component\DependencyInjection\Container $container
	 * @return Client
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
