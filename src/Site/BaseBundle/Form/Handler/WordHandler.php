<?php

namespace Site\BaseBundle\Form\Handler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormInterface;
use Site\BaseBundle\Entity\Theme;
use Site\BaseBundle\Entity\Word;
use Site\BaseBundle\Form\Word\WordNewForm;
use Site\BaseBundle\Form\Word\WordEditForm;

class WordHandler extends DefaultFormHandler
{
  protected function returnSuccess(FormInterface $form)
  {
    /** @var \Site\BaseBundle\Entity\Word $word  */
    $word = $form->getData();
    return new RedirectResponse($this->router->generate('word.edit', array('slug' => $word->getSlug())));
  }

  /**
   * Generates form for new word
   * @param \Site\BaseBundle\Entity\Theme $theme
   * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
   */
  public function generate_new_form(Theme $theme = null)
  {
    $word = new Word();
    $word->setUser($this->user);
    $word->setTheme($theme);

    return $this->form_factory->create(new WordNewForm(), $word);
  }

  /**
   * Generates form for existing word
   * @param \Site\BaseBundle\Entity\Word $word
   * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
   */
  public function generate_edit_form(Word $word)
  {
    return $this->form_factory->create(new WordEditForm(), $word);
  }
}
