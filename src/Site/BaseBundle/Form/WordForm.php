<?php

namespace Site\BaseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ThemeForm extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('theme', null, array('label' => 'theme.name',))

    ;
  }

  public function getSourceChoices()
  {
    return array('ru' => 'Русский');
  }

  public function getTargetChoices()
  {
    return array('en' => 'English');
  }

  /**
   * Returns the name of this type.
   *
   * @return string The name of this type
   */
  public function getName()
  {
    return 'theme';
  }
}