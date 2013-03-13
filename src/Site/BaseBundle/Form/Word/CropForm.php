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
      ->add('crop_x1', 'hidden')
      ->add('crop_y1', 'hidden')
      ->add('crop_x2', 'hidden')
      ->add('crop_y2', 'hidden')
      ->add('image_width', 'hidden');
  }

  public function getName()
  {
    return 'crop_options';
  }
}