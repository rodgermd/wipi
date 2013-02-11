<?php

namespace Site\BaseBundle\Form\Handler;
use Symfony\Component\Form\FormTypeInterface;
use Site\BaseBundle\Entity\Word;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Site\BaseBundle\Form\Steps\WordStepsInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Site\BaseBundle\Entity\User;
use Site\BaseBundle\Form\Exception\StepValidationException;

class WordStepsHandler implements ContainerAwareInterface
{

  /** @var User $user */
  protected $user;
  /** @var EntityManager $em */
  protected $em;
  /** @var Validator $validator */
  protected $validator;
  /** @var Request $request */
  protected $request;
  /** @var FormFactory $form_factory */
  protected $form_factory;
  /** @var Session $session */
  protected $session;

  protected static $session_key = 'WORD_DATA';


  /**
   * Processes form
   * @param WordStepsInterface $type
   * @param null $data
   * @return bool|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
   */
  public function process(WordStepsInterface $type, $data = null)
  {
    if (!$data) {
      $data = $this->getWordInstance();
      call_user_func(array($this, 'load_step' . $type->getStep()), $data);
    }

    $form = $this->form_factory->create($type, $data);

    if ($this->request->isMethod('POST')) {
      $form->bind($this->request);
      if ($form->isValid()) {
        $method = 'save_step' . $type->getStep();
        call_user_func(array($this, $method), $data);
        return true;
      }
    }
    return $form;
  }

  protected function load_step1(Word $word)
  {
    $data  = $this->getSessionData();
    $theme = $this->em->getRepository('SiteBaseBundle:Theme')->find($data['theme']);
    $word->setTheme($theme);
    $word->setSource($data['source']);
    $word->setTarget($data['target']);
  }

  /**
   * Saves step1 data
   * @param \Site\BaseBundle\Entity\Word $word
   */
  protected function save_step1(Word $word)
  {
    $data           = $this->getSessionData();
    $data['theme']  = $word->getTheme()->getId();
    $data['source'] = $word->getSource();
    $data['target'] = $word->getTarget();
    $this->session->set(self::$session_key, $data);
  }

  protected function load_step2(Word $word)
  {
    $this->load_step1($word);
  }

  /**
   * Gets word instance
   * @return \Site\BaseBundle\Entity\Word
   */
  public function getWordInstance()
  {
    $word = new Word();
    $word->setUser($this->user);

    return $word;
  }

  /**
   * Sets the Container.
   *
   * @param ContainerInterface $container A ContainerInterface instance
   *
   * @api
   */
  public function setContainer(ContainerInterface $container = null)
  {
    $this->em           = $container->get('doctrine')->getManager();
    $this->validator    = $container->get('validator');
    $this->user         = $container->get('security.context')->getToken()->getUser();
    $this->request      = $container->get('request');
    $this->form_factory = $container->get('form.factory');
    $this->session      = $container->get('session');
  }

  /**
   * Reads session data
   * @return array
   */
  protected function getSessionData()
  {
    return $this->session->get(self::$session_key);
  }

  /**
   * Stores session data
   * @param array $data
   */
  protected function setSessionData(array $data)
  {
    $this->session->set(self::$session_key, $data);
  }
}