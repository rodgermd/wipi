<?php

namespace Site\BaseBundle\Form\Word;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CropForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('crop_x', 'hidden')
      ->add('crop_y', 'hidden')
      ->add('crop_w', 'hidden')
      ->add('crop_h', 'hidden');
  }

  public function getName()
  {
    return 'crop_options';
  }
}