<?php
namespace Site\BaseBundle\Form\Word\EventListener;

use Site\BaseBundle\Entity\Word;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WordEditFormFieldSubscriber implements EventSubscriberInterface
{
  public static function getSubscribedEvents()
  {
// Tells the dispatcher that you want to listen on the form.pre_set_data
// event and that the preSetData method should be called.
    return array(FormEvents::PRE_SET_DATA => 'preSetData');
  }

  public function preSetData(FormEvent $event)
  {
    /** @var Word $data */
    $data = $event->getData();
    $form = $event->getForm();

    if (null === $data) {
      return;
    }

    if ($data->getImageFilename()) {
      $form->add('crop_options', 'crop_options', array(
        'required'      => false,
        'property_path' => null
      ));
    }
  }
}