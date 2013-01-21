<?php

namespace Site\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle', 'attr' => array('placeholder' => 'Email')))
      ->add('plainPassword', 'password', array(
      'translation_domain' => 'FOSUserBundle',
      'label'              => 'form.password',
      'attr'               => array('placeholder' => 'Password')));
  }

  public function getName()
  {
    return 'wipi_user_registration_form';
  }
}