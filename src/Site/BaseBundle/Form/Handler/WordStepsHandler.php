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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Site\BaseBundle\Entity\User;
use Site\BaseBundle\Form\Exception\StepValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
  /** @var Router $router */
  protected $router;
  /** @var FormFactory $form_factory */
  protected $form_factory;
  /** @var Session $session */
  protected $session;
  /** @var ContainerInterface $container */
  protected $container;

  protected static $session_key = 'WORD_DATA';


  /**
   * Processes form
   * @param WordStepsInterface $type
   * @param \Site\BaseBundle\Entity\Word $data
   * @return bool|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
   */
  public function process(WordStepsInterface $type, Word $data = null)
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
        return call_user_func(array($this, $method), $data);
      }
    }

    return array('form' => $form->createView(), 'theme' => $data->getTheme());
  }

  /**
   * Cleanups session data
   */
  public function cleanup()
  {
    $this->setSessionData(array());
  }

  /**
   * Loads step 1 data
   * @param \Site\BaseBundle\Entity\Word $word
   */
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
    $this->replaceSessionData(array(
      'theme'  => $word->getTheme()->getId(),
      'source' => $word->getSource(),
      'target' => $word->getTarget(),
    ));

    return new RedirectResponse($this->router->generate('word.new.step2'));
  }

  /**
   * Loads step 2 data
   * @param \Site\BaseBundle\Entity\Word $word
   */
  protected function load_step2(Word $word)
  {
    $this->load_step1($word);

    $data = $this->getSessionData();

    if (array_key_exists('soundfile_name', $data) && $data['soundfile_name']) {
      $stored_filename = $this->container->getParameter('wipi.temp_folders.soundfiles') . '/' . $data['soundfile_name'];
      $uploaded        = new UploadedFile(
        $stored_filename,
        'uploaded-file.' . $data['soundfile_extension'],
        null,
        filesize($stored_filename));
      $word->setSoundfile($uploaded);
    }
  }

  /**
   * Saves step 2 data
   * @param \Site\BaseBundle\Entity\Word $word
   */
  protected function save_step2(Word $word)
  {
    $soundfile = $word->getSoundfile();
    if ($soundfile) {
      $soundfile->move($this->container->getParameter('wipi.temp_folders.soundfiles'), $soundfile->getFilename());
    }
    $this->replaceSessionData(array(
      'soundfile_name'      => $soundfile ? $soundfile->getFilename() : null,
      'soundfile_extension' => $soundfile ? $soundfile->getExtension() : null,
    ));

    return new RedirectResponse($this->router->generate('word.new.step3'));
  }

  /**
   * Loads step 3 file
   * @param \Site\BaseBundle\Entity\Word $word
   */
  protected function load_step3(Word $word)
  {
    $this->load_step2($word);

    $data = $this->getSessionData();

    if (array_key_exists('imagefile_name', $data) && $data['imagefile_name']) {
      $stored_filename = $this->container->getParameter('wipi.temp_folders.imagefiles') . '/' . $data['imagefile_name'];
      $uploaded        = new UploadedFile(
        $stored_filename,
        'uploaded-file.' . $data['imagefile_extension'],
        null,
        filesize($stored_filename));
      $word->setImagefile($uploaded);
    }
  }

  /**
   * Saves step 3 data
   * @param \Site\BaseBundle\Entity\Word $word
   */
  protected function save_step3(Word $word)
  {
    $this->save_object($word);
    $this->cleanup();
    $this->session->getFlashBag()->add('success', $this->container->get('translator')->trans('word.flashes.created', array('%word%' => $word->getSource()), 'word_forms'));
    return new RedirectResponse($this->router->generate('word.show', array('slug' => $word->getSlug())));
  }

  /**
   * Saves object into DB
   * @param \Site\BaseBundle\Entity\Word $word
   * @return \Site\BaseBundle\Entity\Word
   * @throws \Site\BaseBundle\Form\Exception\StepValidationException
   */
  protected function save_object(Word $word)
  {
    $errors = $this->validator->validate($word);
    if (count($errors)) throw new StepValidationException($errors);

    $this->em->persist($word);
    $this->em->flush();

    return $word;
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
    $this->router       = $container->get('router');
    $this->form_factory = $container->get('form.factory');
    $this->session      = $container->get('session');
    $this->container    = $container;
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

  protected function replaceSessionData(array $data)
  {
    $session_data = $this->getSessionData();
    $session_data = array_merge($session_data, $data);
    $this->setSessionData($session_data);
  }
}