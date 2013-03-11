<?php
namespace Site\BaseBundle\Form\Word;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WordImageForm extends WordEditForm
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('imagefile', 'file', array(
      'data_class'=> 'Symfony\Component\HttpFoundation\File\File',
      'required' => true));
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->replaceDefaults(array(
      'validation_groups' => 'image',
      'csrf_protection'   => false,
    ));
  }
}