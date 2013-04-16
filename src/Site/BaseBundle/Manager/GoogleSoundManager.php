<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 16.04.13
 * Time: 22:14
 * To change this template use File | Settings | File Templates.
 */

namespace Site\BaseBundle\Manager;

use Buzz\Browser;
use Doctrine\ORM\EntityManager;
use Site\BaseBundle\Entity\GoogleSound;
use Site\BaseBundle\Manager\Exception\SoundCouldNotBeRetrieved;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\Storage\GaufretteStorage;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class GoogleSoundManager
{
  protected $browser;
  protected $sound_repository;
  protected $em;
  protected $vich;
  protected $storage;

  public function __construct(Browser $browser, EntityManager $em, UploaderHelper $vich, GaufretteStorage $vich_storage)
  {
    $this->browser          = $browser;
    $this->em               = $em;
    $this->sound_repository = $em->getRepository('SiteBaseBundle:GoogleSound');
    $this->vich             = $vich;
    $this->storage          = $vich_storage;
  }

  /**
   * Gets binary response of sound file
   * @param $culture
   * @param $word
   * @throws Exception\SoundCouldNotBeRetrieved
   * @param $word
   * @return Response
   */
  public function getSound($culture, $word)
  {
    $word     = mb_strtolower($word, 'UTF8');
    $existing = $this->getStored($culture, $word);

    if (!$existing) {
      $content = $this->browser->get(strtr('http://translate.google.com/translate_tts?q=%word%&tl=%culture%', array(
        '%word%'    => $word,
        '%culture%' => $culture
      )));

      if (!$content->getStatusCode() == 200) throw new SoundCouldNotBeRetrieved();

      $existing = $this->store($culture, $word, $content->getContent());
    }

    $response = new Response(file_get_contents($this->storage->resolvePath($existing, 'file')));
    $response->headers->add(array(
      'Content-Type' => 'audio/mpeg',
      'Cache-Control' => 'private, max-age=86400'));
    return $response;
  }

  protected function getStored($culture, $word)
  {
    return $this->sound_repository->findOneBy(array('culture' => $culture, 'word' => $word));
  }

  /**
   * Creates new record
   * @param $culture
   * @param $word
   * @param $content
   * @return GoogleSound
   */
  protected function store($culture, $word, $content)
  {
    $record = new GoogleSound();
    $record->setWord($word)->setCulture($culture);

    $temp_file_name = tempnam(sys_get_temp_dir(), uniqid() . '.mpga');
    $fh             = fopen($temp_file_name, 'wb');
    fwrite($fh, $content);
    fclose($fh);

    $uploaded_file = new UploadedFile($temp_file_name, uniqid() . '.mpga', null, filesize($temp_file_name));
    $record->setFile($uploaded_file);
    $this->em->persist($record);
    $this->em->flush($record);

    return $record;
  }

}