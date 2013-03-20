<?php

namespace Site\BaseBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
  /**
   * {@inheritDoc}
   */
  public function getConfigTreeBuilder()
  {
    $treeBuilder = new TreeBuilder();
    $rootNode    = $treeBuilder->root('wipi');

    $rootNode->children()
      ->arrayNode('flickr')->children()
        ->scalarNode('key')->isRequired()->end()
        ->scalarNode('secret')->isRequired()->end()
        ->scalarNode('userid')->isRequired()->end()
        ->scalarNode('endpoint')->defaultValue('http://api.flickr.com/services/rest/?')->end()
      ->end()
    ->end();


    return $treeBuilder;
  }
}
