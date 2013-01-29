<?php

namespace Site\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Site\BaseBundle\Entity\Theme;
use Site\BaseBundle\Entity\Word;

use Site\BaseBundle\Form\WordStep1Form;

/**
 * @Route("/word")
 */
class WordController extends Controller
{
  /**
   * @Route("/new/{slug}", name="word.new", requirements={"slug"=".+"})
   * @Template
   * @Secure(roles="ROLE_USER")
   * @param \Site\BaseBundle\Entity\Theme $theme
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function newAction(Theme $theme)
  {
    $word = new Word();
    $word->setUser($this->getUser());
    $word->setTheme($theme);

    $form = $this->createForm(new WordStep1Form(), $word);
    if ($this->getRequest()->isMethod('POST'))
    {
      $form->bind($this->getRequest());
      if ($form->isValid())
      {
        $this->getDoctrine()->getManager()->persist($word);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('word.edit.step2', array('slug' => $word->getSlug())));
      }

    }
    return array('form' => $form->createView(), 'theme' => $theme);
  }

  /**
   * @Route("/{slug}/step2", name="word.edit.step2", requirements={"slug"=".+"})
   * @Template
   * @Secure(roles="ROLE_USER")
   * @param \Site\BaseBundle\Entity\Word $word
   */
  public function word_step2Action(Word $word)
  {

  }

  /**
   * @Route("/find-translation/{slug}", name="word.find_translation", requirements={"slug"=".+"})
   */
  public function translationAction(Theme $theme)
  {

  }
}