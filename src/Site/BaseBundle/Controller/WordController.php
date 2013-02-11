<?php

namespace Site\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Site\BaseBundle\Helper\TranslationHelper;
use Symfony\Component\HttpFoundation\Response;

use Site\BaseBundle\Entity\Theme;
use Site\BaseBundle\Entity\Word;

use Site\BaseBundle\Form\Handler\WordStepsHandler;

use Site\BaseBundle\Form\Steps\Step1Type;
use Site\BaseBundle\Form\Steps\Step2Type;

/**
 * @Route("/word")
 */
class WordController extends Controller
{
  /**
   * @Route("/new/based-on-theme/{slug}", name="word.new", requirements={"slug"=".+"})
   * @Template
   * @Secure(roles="ROLE_USER")
   * @param \Site\BaseBundle\Entity\Theme $theme
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function newAction(Theme $theme)
  {
    /** @var WordStepsHandler $handler  */
    $handler = $this->get('wipi.word.steps_handler');
    $word = $handler->getWordInstance();
    $word->setTheme($theme);

    $result = $handler->process(new Step1Type(), $word);
    if ($result === true) {
      return $this->redirect($this->generateUrl('word.new.step2'));
    }

    return array('form' => $result->createView(), 'theme' => $theme);
  }

  /**
   * @Route("/new/step2", name="word.new.step2")
   * @Template
   * @Secure(roles="ROLE_USER")
   */
  public function word_new_step2Action()
  {
    /** @var WordStepsHandler $handler  */
    $handler = $this->get('wipi.word.steps_handler');
    $result = $handler->process(new Step2Type());
    if ($result === true) {
      return $this->redirect($this->generateUrl('word.new.step3'));
    }
    return array('form' => $result->createView(), 'theme' => $result->getData()->getTheme());
  }

  /**
   * @Route("/new/step3", name="word.new.step3")
   * @Template
   * @Secure(roles="ROLE_USER")
   */
  public function word_new_step3Action()
  {
    /** @var WordStepsHandler $handler  */
    $handler = $this->get('wipi.word.steps_handler');
    $result = $handler->process(new Step3Type());
    if ($result === true) {
      return $this->redirect($this->generateUrl('word.new.step4'));
    }
    return array('form' => $result->createView(), 'theme' => $result->getData()->getTheme());
  }


  /**
   * @Route("/find-translation/{slug}", name="word.find_translation", requirements={"slug"=".+"})
   * @Method("POST")
   * @Secure(roles="ROLE_USER")
   */
  public function translationAction(Theme $theme)
  {
    /** @var TranslationHelper $translations_helper */
    $translations_helper = $this->get('wipi.translator');
    $result              = $translations_helper->find_group_by_word($theme->getSourceCulture(), $theme->getTargetCulture(), $this->getRequest()->get('word'));
    $response            = new Response(json_encode($result));
    $response->headers->set('Content-type', 'application/json');
    return $response;
  }
}