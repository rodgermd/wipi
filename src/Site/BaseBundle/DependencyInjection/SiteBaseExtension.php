<?php

namespace Site\BaseBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SiteBaseExtension extends Extension
{
  /**
   * {@inheritDoc}
   */
  public function load(array $configs, ContainerBuilder $container)
  {
    $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
    $loader->load('services.yml');
    $loader->load('parameters.yml');
    $loader->load('forms.yml');
    $loader->load('managers.yml');
    $loader->load('apis.yml');

    $configuration = new Configuration();
    $processor = new Processor();

    $config = $processor->processConfiguration($configuration, $configs);

    foreach($config['flickr'] as $key => $value)
    {
      $container->setParameter('wipi_flickr_' . $key, $value);
    }
  }
}
