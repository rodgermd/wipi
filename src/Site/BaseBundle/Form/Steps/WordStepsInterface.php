<?php

namespace Site\BaseBundle\Form\Steps;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;


interface WordStepsInterface
{
  public function getStep();
  public function buildView(FormView $view, FormInterface $form, array $options);
}