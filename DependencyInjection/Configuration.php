<?php
namespace Uecode\GearmanBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * @author Aaron Scherer
 * @date 10/8/12
 */

/**
 * Configuration for the Gearman Bundle
 */
class Configuration implements \Symfony\Component\Config\Definition\ConfigurationInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root( 'uecode' );

		$rootNode
			->arrayNode( 'gearman' )
				->children()
					->fixXmlConfig( 'connection' )
					->arrayNode( 'connections' )
						->requiresAtLeadOneElement()
						->useAttributeAsKey( 'name' )
						->prototype( 'array' )
							->children()
								->scalarNode( 'host' )
									->defaultValue( '127.0.0.1' )
								->end()
								->scalarNode( 'port' )
									->defaultValue( '4730' )
								->end()
							->end()
						->end()
					->end()
					->scalarNode( 'debug' )
						->defaultValue( 'false' )
					->end()
				->end()
			->end()
		;

		return $treeBuilder;
	}
}
