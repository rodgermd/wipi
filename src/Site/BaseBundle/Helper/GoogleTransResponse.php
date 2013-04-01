<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 01.04.13
 * Time: 21:45
 * To change this template use File | Settings | File Templates.
 */

namespace Site\BaseBundle\Helper;


use Site\BaseBundle\Helper\Exception\TranslationException;

class GoogleTransResponse
{
  public $translations = array();

  public function __construct($data = array())
  {
    if (!count($data) > 2) throw new TranslationException();
    if (!is_array($data[1])) throw new TranslationException();
    @$data = $data[1][0][2];
    if (!is_array($data)) throw new TranslationException();
    foreach($data as $translation) $this->translations[] = new GoogleTransTranslation($translation);
  }

}