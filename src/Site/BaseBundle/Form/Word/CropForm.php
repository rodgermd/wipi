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
      ->add('crop_x1', 'text')
      ->add('crop_y1', 'text')
      ->add('crop_x2', 'text')
      ->add('crop_y2', 'text')
      ->add('image_width', 'text');
  }

  public function getName()
  {
    return 'crop_options';
  }
}