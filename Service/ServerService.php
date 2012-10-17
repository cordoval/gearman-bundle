<?php
/**
 * @author Aaron Scherer
 * @date 10/8/12
 */
namespace Uecode\GearmanBundle\Service;
use Symfony\Component\DependencyInjection\Container;

// Gearman Classes
use Uecode\GearmanBundle\Gearman\Server;

class ClientService
{

	/**
	 * @var Container;
	 */
	private $container;

	/**
	 * @var Server
	 */
	private $server;

	/**
	 * @param \Symfony\Component\DependencyInjection\Container $container
	 */
	public function init( Container $container )
	{
		$this
			->setContainer( $container )
			->setServer(
				new Server(
					$container->getParameter( 'uecode.gearman.server' )
				)
			)
		;
	}

	public function start( )
	{
		$this->getServer();
	}

	/**
	 * @param \Uecode\GearmanBundle\Gearman\Server $server
	 */
	protected function setServer( Server $server )
	{
		$this->server = $server;
	}

	/**
	 * @return \Uecode\GearmanBundle\Gearman\Server
	 */
	protected function getServer()
	{
		return $this->server;
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
