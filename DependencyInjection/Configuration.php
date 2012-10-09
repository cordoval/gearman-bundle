<?php
namespace Uecode\GearmanBundle\DependancyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
			->append( $this->getConnectionsNode() )
			->scalarNode( 'debug' )
				->defaultValue( 'false' )
			->end()
		;

		return $treeBuilder;
	}

	/**
	 * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
	 */
	private function getConnectionsNode()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root( 'connections' );

		$connectionNode = $node
			->requiresAtLeastOneElement()
			->useAttributeAsKey( 'name' )
			->prototype( 'array' )
		;
		return $connectionNode;
	}

	/**
	 * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
	 */
	private function configureGearmanConnectionNode( ArrayNodeDefinition $node )
	{
		$node
			->children()
				->scalarNode( 'host' )
					->defaultValue( '127.0.0.1' )
				->end()
				->scalarNode( 'port' )
					->defaultValue( '4730' )
				->end()
			->end();

	}

}
