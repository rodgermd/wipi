<?php

namespace Site\BaseBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Site\BaseBundle\DependencyInjection\Compiler\CreateFoldersCompilerPass;

class SiteBaseBundle extends Bundle
{
  public function build(ContainerBuilder $container)
  {
    parent::build($container);

    $container->addCompilerPass(new CreateFoldersCompilerPass());
  }
}
