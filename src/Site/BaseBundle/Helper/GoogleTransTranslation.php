<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 01.04.13
 * Time: 21:46
 * To change this template use File | Settings | File Templates.
 */

namespace Site\BaseBundle\Helper;


use Site\BaseBundle\Helper\Exception\TranslationException;

class GoogleTransTranslation
{
  public $word;
  public $relevancy;
  public $reverse_values = array();

  public function __construct($data = array())
  {
    if (!count($data)) throw new TranslationException();
    $this->word           = $data[0];
    $this->relevancy      = (int) @$data[2];
    $this->reverse_values = $data[1];
  }
}