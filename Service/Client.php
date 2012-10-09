<?php
/**
 * @author Aaron Scherer
 * @date 10/8/12
 */
namespace Uecode\GearmanBundle;
use Symfony\Component\DependencyInjection\Container;


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

	public function init( Container $container )
	{
		$this
			->setContainer( $container )
			->setConfigs( $container->getParameter( 'uecode_gearman' ) );
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
	 * @return array
	 */
	protected function getConfigs()
	{
		return $this->configs;
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
