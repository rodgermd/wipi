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
      ->add('plainPassword', 'repeated', array(
      'type' => 'password',
      'options' => array('translation_domain' => 'FOSUserBundle'),
      'first_options' => array('label' => 'form.password', 'attr' => array('placeholder' => 'Password')),
      'second_options' => array('label' => 'form.password_confirmation', 'attr' => array('placeholder' => 'Confirm password')),
      'invalid_message' => 'fos_user.password.mismatch',
    ))
    ;
  }

    public function getName()
  {
    return 'wipi_user_registration_form';
  }
}