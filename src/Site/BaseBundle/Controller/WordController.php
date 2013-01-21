<?php

namespace Site\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Site\BaseBundle\Entity\Theme;
use Site\BaseBundle\Entity\Word;

/**
 * @Route("/word")
 */
class WordController extends Controller
{
  /**
   * @Route("/new/{slug}", name="word.new.theme", requirements={"slug"=".+"})
   * @Template
   * @Secure(roles="ROLE_USER")
   * @param \Site\BaseBundle\Entity\Theme $theme
   */
  public function new_themedAction(Theme $theme)
  {
    $word = new Word();
    $word->setUser($this->getUser());
    $word->setTheme($theme);


  }
}