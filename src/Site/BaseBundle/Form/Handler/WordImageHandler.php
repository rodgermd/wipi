<?php
namespace Site\BaseBundle\Form\Handler;

use Site\BaseBundle\Entity\Word;
use Site\BaseBundle\Form\Word\WordImageForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class WordImageHandler extends DefaultFormHandler
{
  public function process_separate_image_upload(Word $word)
  {
    if (!$word->getUser()->equals($this->user)) return $this->returnForbidden($word);
    return $this->process($this->form_factory->create(new WordImageForm(), $word));
  }

  public function returnSuccess(FormInterface $form)
  {
    return $form;
  }
}