<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 16.04.13
 * Time: 22:59
 * To change this template use File | Settings | File Templates.
 */

namespace Site\BaseBundle\Controller;

use Site\BaseBundle\Manager\Exception\SoundCouldNotBeRetrieved;
use Site\BaseBundle\Manager\GoogleSoundManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SoundController
 * @package Site\BaseBundle\Controller
 * @Route("/sound")
 */
class SoundController extends Controller
{

  /**
   * @Route("/autosuggest/{culture}/{word}.mp3", name="sound.autosuggested", requirements={"culture"="\w{2}"})
   * @Secure(roles="ROLE_USER")
   */
  public function googleAction($culture, $word)
  {
    /** @var GoogleSoundManager $manager */
    $manager = $this->get('wipi.manager.google_sound');

    try {
      return $manager->getSound($culture, $word);
    } catch (SoundCouldNotBeRetrieved $e) {
    }
    return new Response($e->getMessage(), 500);
  }
}