<?php
/**
 * @author Aaron Scherer
 * @date 10/8/12
 */
namespace Uecode\Gearman;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Uecode\Gearman\DependancyInjection\Configuration;
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
		$configuration = new Configuration();
		$configuration = $this->processConfiguration( $configuration, $config );

		$loader = new Loader\YamlFileLoader( $container, new FileLocator( __DIR__ . '/../Resources/config' ) );
		$loader->load( 'services.yml' );
	}
}
