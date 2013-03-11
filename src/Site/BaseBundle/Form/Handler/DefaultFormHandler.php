<?php
namespace Site\BaseBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Router;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Site\BaseBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultFormHandler
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

  public function __construct(Container $container)
  {
    $this->container    = $container;
    $this->em           = $container->get('doctrine')->getManager();
    $this->validator    = $container->get('validator');
    $this->user         = $container->get('security.context')->getToken()->getUser();
    $this->request      = $container->get('request');
    $this->router       = $container->get('router');
    $this->form_factory = $container->get('form.factory');
    $this->session      = $container->get('session');
  }

  /**
   * Processes form
   * @param \Symfony\Component\Form\FormInterface $form
   * @return \Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function process(FormInterface $form)
  {
    if ($this->request->isMethod('POST')) {
      $form->bind($this->request);
      if ($form->isValid()) {
        $this->save($form);
        return $this->returnSuccess($form);
      }
    }
    return $this->returnNotValidated($form);
  }

  /**
   * Save handler
   * @param \Symfony\Component\Form\FormInterface $form
   */
  protected function save(FormInterface $form)
  {
    $data = $form->getData();
    $this->em->persist($data);
    $this->em->flush();
  }

  /**
   * Returns success response
   * @param \Symfony\Component\Form\FormInterface $form
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  protected function returnSuccess(FormInterface $form)
  {
    return new RedirectResponse($this->request->headers->get('referer'));
  }

  /**
   * Returns not validated response
   * @param \Symfony\Component\Form\FormInterface $form
   * @return \Symfony\Component\Form\FormInterface
   */
  protected function returnNotValidated(FormInterface $form)
  {
    return $form;
  }

  /**
   * Returns forbidden
   * @param $object
   */
  public function returnForbidden($object)
  {
    $this->session->getFlashBag()->add('error',
      $this->container->get('translator')->trans(
        'You dont have permission to modify requested object: %object%',
        array('%object%' => $object)));

    return new RedirectResponse($this->router->generate('homepage'));
  }

}