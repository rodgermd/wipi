<?php

namespace Site\BaseBundle\Controller;

use JMS\Serializer\Serializer;
use Site\BaseBundle\Helper\GoogleTransResponse;
use Site\BaseBundle\Manager\Exception\WrongImageUrlException;
use Site\BaseBundle\Manager\ImageManager;
use Site\BaseBundle\Manager\PhotoSearchManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Site\BaseBundle\Helper\TranslationHelper;
use Symfony\Component\HttpFoundation\Response;

use Site\BaseBundle\Form\Word\WordNewForm;
use Site\BaseBundle\Form\Word\WordEditForm;

use Site\BaseBundle\Entity\Theme;
use Site\BaseBundle\Entity\Word;

use Site\BaseBundle\Form\Handler\WordHandler;
use Site\BaseBundle\Form\Handler\WordImageHandler;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/word")
 */
class WordController extends Controller
{

  /**
   * @Route("/{slug}/show", name="word.show", requirements={"slug"=".+"})
   * @Template
   * @param \Site\BaseBundle\Entity\Word $word
   * @return array
   */
  public function showAction(Word $word)
  {
    return compact('word');
  }

  /**
   * @Route("/{slug}/new", name="word.new", requirements={"slug"=".+"})
   * @Template
   * @Secure(roles="ROLE_USER")
   * @param \Site\BaseBundle\Entity\Theme $theme
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function newAction(Theme $theme)
  {
    /** @var WordHandler $handler */
    $handler = $this->get('wipi.word.form_handler');
    $form    = $handler->generate_new_form($theme);
    $result  = $handler->process($form);
    if ($result instanceof Response) return $result;
    return array('form' => $result->createView(), 'theme' => $theme);
  }

  /**
   * @Route("/{slug}/edit", name="word.edit", requirements={"slug"=".+"})
   * @Secure(roles="ROLE_USER")
   * @Template
   * @param \Site\BaseBundle\Entity\Word $word
   * @return \Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function editAction(Word $word)
  {
    /** @var WordHandler $handler */
    $handler = $this->get('wipi.word.form_handler');
    $form    = $handler->generate_edit_form($word);
    $result  = $handler->process($form);
    if ($result instanceof Response) {
      /** @var ImageManager $image_manager */
      $image_manager = $this->get('wipi.manager.image');

      // resize and crop base image
      $image_manager->crop_image($word, $word->getCropOptions());
      return $result;
    }
    return array('form' => $result->createView(), 'word' => $word, 'theme' => $word->getTheme());
  }

  /**
   * Delete word
   * @Route("/{slug}/delete", name="word.delete", requirements={"slug"=".+"})
   * @Secure(roles="ROLE_USER")
   * @param Word $word
   */
  public function deleteAction(Word $word)
  {
    if (!$word->getUser()->equals($this->getUser())) throw new AccessDeniedException();

    $em = $this->getDoctrine()->getManager();
    $em->remove($word);
    $em->flush();

    return new RedirectResponse($this->getRequest()->headers->get('referer'));
  }

  /**
   * Processes single image file upload
   * @Route("{slug}/upload-image", name="word.upload.image", requirements={"slug"=".+", "_method"="POST"})
   * @Template("SiteBaseBundle:Word:_form_word_image.html.twig")
   * @param \Site\BaseBundle\Entity\Word $word
   * @return array|\Symfony\Component\HttpFoundation\Response
   */
  public function submitImage(Word $word)
  {
    /** @var WordImageHandler $handler */
    $handler = $this->get('wipi.word.image_handler');
    $result  = $handler->process_separate_image_upload($word);
    if ($result instanceof Response) return $result;
    return array('form' => $result->createView(), 'word' => $word, 'theme' => $word->getTheme());
  }

  /**
   * @Route("{slug}/submit-image-url", name="word.upload_from_web", requirements={"slug"=".+", "_method"="POST"})
   * @param Word $word
   */
  public function sumbitImageUrl(Word $word)
  {
    $url = $this->getRequest()->get('url');

    /** @var ImageManager $manager */
    $manager = $this->get('wipi.manager.image');
    try {
      $manager->upload_from_url($word, $url);
      return $this->render('SiteBaseBundle:Word:_form_word_image.html.twig', $this->editAction($word));
    } catch (WrongImageUrlException $e) {
    }

    return new Response(500);
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
    /** @var Serializer $serializer  */
    $serializer = $this->get('jms_serializer');
    /** @var GoogleTransResponse $result  */
    $result              = $translations_helper->translate_google_suggest($theme->getSourceCulture(), $theme->getTargetCulture(), $this->getRequest()->get('word'));
    $response            = new Response($serializer->serialize($result, 'json'));
    $response->headers->set('Content-type', 'application/json');
    return $response;
  }

  /**
   * @Route("/search-photos/{slug}", name="word.search_photos", requirements={"slug"=".+"})
   * @Template()
   * @param Word $word
   * @return array
   */
  public function searchFlickrAction(Word $word)
  {
    /** @var PhotoSearchManager $manager */
    $manager = $this->container->get('wipi.manager.photo_search');
    return array(
      'word'   => $word,
      'images' => $manager->search(implode(',', array($word->getSource(), $word->getTarget()))));
  }
}