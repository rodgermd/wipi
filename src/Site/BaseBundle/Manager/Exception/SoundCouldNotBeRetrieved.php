<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 16.04.13
 * Time: 22:57
 * To change this template use File | Settings | File Templates.
 */

namespace Site\BaseBundle\Manager\Exception;


class SoundCouldNotBeRetrieved extends \Exception {
  protected $message = 'The sound content could not be retrieved';
}