<?php
namespace Uecode\Gearman\DependancyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Aaron Scherer
 * @date 10/8/12
 */

/**
 * Configuration for the Gearman Bundle
 */
class Configuration implements ConfigurationInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root( 'uecode_gearman' );

		$rootNode
			->arrayNode( 'connection' )
				->children()
					->scalarNode( 'host' )
						->isRequired()
						->defaultValue( '127.0.0.1' )
						->cannotBeEmpty()
					->end()
					->scalarNode( 'port' )
						->isRequired()
						->defaultValue( '4730' )
						->cannotBeEmpty()
					->end()
				->end()
			->end()
			->scalarNode( 'debug' )
				->defaultValue( 'false' )
			->end()
		;

		return $treeBuilder;
	}

}
