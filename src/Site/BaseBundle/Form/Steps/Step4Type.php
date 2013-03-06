<?php

namespace Site\BaseBundle\Form\Steps;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Step4Type extends Step1Type
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('note', 'textarea', array('required' => false));
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->replaceDefaults(array(
      'validation_groups' => 'step4'
    ));
  }

  /**
   * Returns the name of this type.
   *
   * @return string The name of this type
   */
  public function getName()
  {
    return 'word_sound';
  }

  public function getStep()
  {
    return 4;
  }
}