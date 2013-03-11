<?php

namespace Site\BaseBundle\Form\Word;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WordNewForm extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('source', null, array('label' => 'word.source', 'translation_domain' => 'word_forms',))
      ->add('target', null, array('label' => 'word.target', 'translation_domain' => 'word_forms',));
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->replaceDefaults(array(
      'data_class'        => 'Site\BaseBundle\Entity\Word',
      'validation_groups' => 'step1'
    ));
  }


  /**
   * Returns the name of this type.
   *
   * @return string The name of this type
   */
  public function getName()
  {
    return 'word';
  }
}