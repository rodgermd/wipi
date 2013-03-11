<?php
namespace Site\BaseBundle\Form\Word;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WordEditForm extends WordNewForm
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    parent::buildForm($builder, $options);

    $builder
      ->add('imagefile', 'file', array('data_class'=> 'Symfony\Component\HttpFoundation\File\File', 'required' => false))
      ->add('soundfile', 'file', array('data_class' => null, 'required' => false))
      ->add('note', 'textarea', array('required' => false))
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->replaceDefaults(array(
      'validation_groups' => 'Default'
    ));
  }
}