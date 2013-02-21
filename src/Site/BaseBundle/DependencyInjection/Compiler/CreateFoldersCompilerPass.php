<?php

namespace Site\BaseBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CreateFoldersCompilerPass implements CompilerPassInterface
{

  /**
   * You can modify the container here before it is dumped to PHP code.
   *
   * @param ContainerBuilder $container
   *
   * @api
   */
  public function process(ContainerBuilder $container)
  {
    $sounds_path = $container->getParameter('wipi.temp_folders.soundfiles');
    $images_path = $container->getParameter('wipi.temp_folders.imagefiles');


    foreach (array($sounds_path, $images_path) as $path) {
      if (!is_dir($path)) {
        mkdir($path, 0777, true);
      }
    }
  }
}
