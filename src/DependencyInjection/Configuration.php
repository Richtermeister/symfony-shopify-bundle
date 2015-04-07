<?php
namespace CodeCloud\Bundle\ShopifyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
	/**
	 * Generates the configuration tree builder for the shopify configuration entries
	 *
	 * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();

		$rootNode = $treeBuilder->root('codecloud_shopify');

		$rootNode->children()
						->arrayNode('credentials')->cannotBeEmpty()
							->children()
								->scalarNode('api_key')->cannotBeEmpty()->end()
								->scalarNode('shared_secret')->cannotBeEmpty()->end()
							->end()
						->end()
						->arrayNode('oauth')
							->children()
								->scalarNode('step1')->cannotBeEmpty()->end()
								->scalarNode('step2')->cannotBeEmpty()->end()
								->scalarNode('step3')->cannotBeEmpty()->end()
							->end()
						->end()
						->arrayNode('twig')
							->children()
								->scalarNode('enabled_embedded_helpers')->end()
							->end()
						->end()
				->end();

		return $treeBuilder;
	}
}