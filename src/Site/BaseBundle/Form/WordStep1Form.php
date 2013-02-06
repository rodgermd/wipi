<?php

namespace Site\BaseBundle\Form;

  use Symfony\Component\Form\AbstractType;
  use Symfony\Component\Form\FormBuilderInterface;

class WordStep1Form extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('source', null, array('label' => 'word.source', 'translation_domain' => 'word_forms'))
      ->add('target', null, array('label' => 'word.target', 'translation_domain' => 'word_forms',))
    ;
  }

  /**
   * Returns the name of this type.
   *
   * @return string The name of this type
   */
  public function getName()
  {
    return 'word_source';
  }
}