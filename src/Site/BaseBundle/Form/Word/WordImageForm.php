<?php
namespace Site\BaseBundle\Form\Word;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WordImageForm extends WordEditForm
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    parent::buildForm($builder, $options);

    foreach($builder->all() as $name => $field)
    {
      if ($name == 'image_file') {
        $field->setAttribute('required', true);
      }
      else {
        $field->setAttribute('property_path', false);
      }
    }

  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->replaceDefaults(array(
      'validation_groups' => 'image',
      'csrf_protection'   => false,
    ));
  }
}