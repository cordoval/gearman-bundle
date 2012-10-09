<?php
/**
 * @author Aaron Scherer
 * @date 10/8/12
 */
namespace Uecode\GearmanBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Uecode Gearman Extension
 */
class UecodeGearmanExtension extends Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function load( array $config, ContainerBuilder $container )
	{
		$configuration = new \Uecode\Gearman\DependencyInjection\Configuration();
		$configuration = $this->processConfiguration( $configuration, $config );

		$loader = new Loader\YamlFileLoader( $container, new FileLocator( __DIR__ . '/../Resources/config' ) );
		$loader->load( 'services.yml' );
	}
}
