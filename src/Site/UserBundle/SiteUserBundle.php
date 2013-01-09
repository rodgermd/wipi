<?php

namespace Site\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use FOS\UserBundle\FOSUserBundle;

class SiteUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}
