<?php

namespace Site\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Site\BaseBundle\Entity\Theme;
use Site\BaseBundle\Entity\Word;
use Site\BaseBundle\Form\ThemeForm;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/theme")
 */
class ThemeController extends Controller {

  /**
   * Shows themes accessible to the current user
   * @Route("/", name="themes.index")
   * @Secure(roles="ROLE_USER")
   * @Template
   */
  public function indexAction()
  {
    return array('themes' => $this->getUser()->getThemes());
  }

  /**
   * New theme action
   * @Route("/new", name="theme.new")
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   * @Secure(roles="ROLE_USER")
   * @Template
   */
  public function newAction(Request $request)
  {
    $theme = new Theme();
    $theme->setUser($this->getUser());
    $form = $this->createForm(new ThemeForm(), $theme);

    if ($request->isMethod('POST'))
    {
      $form->bind($request);
      if ($form->isValid())
      {
        $em = $this->getDoctrine()->getManager();
        $em->persist($theme);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Theme was created successfully');
        return $this->redirect($this->generateUrl('theme.show', array('slug' => $theme->getSlug())));
      }
    }

    return array('form' => $form->createView());
  }

  /**
   * @Route("/edit/{slug}", name="theme.edit", requirements={"slug"=".+"})
   * @Secure(roles="ROLE_USER")
   * @Template
   * @param Theme $theme
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
   */
  public function editAction(Theme $theme)
  {
    if (!$theme->getUser()->equals($this->getUser())) throw new AccessDeniedException();

    $form = $this->createForm(new ThemeForm(), $theme);

    if ($this->getRequest()->isMethod('POST'))
    {
      $form->bind($this->getRequest());
      if ($form->isValid())
      {
        $em = $this->getDoctrine()->getManager();
        $em->persist($theme);
        $em->flush();

        $this->getRequest()->getSession()->getFlashBag()->add('success', 'Theme was updated successfully');
        return $this->redirect($this->generateUrl('theme.show', array('slug' => $theme->getSlug())));
      }
    }

    return array('form' => $form->createView(), 'theme' => $theme);
  }

  /**
   * Shows theme
   * @Route("/{slug}", name="theme.show", requirements={"slug"=".+"})
   * @Secure(roles="ROLE_USER")
   * @Template
   * @param \Site\BaseBundle\Entity\Theme $theme
   * @return array
   */
  public function showAction(Theme $theme)
  {
    return compact('theme');
  }
}