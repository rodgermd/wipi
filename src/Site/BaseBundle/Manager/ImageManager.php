<?php
namespace Site\BaseBundle\Manager;

use Doctrine\ORM\EntityManager;
use Imagine\Imagick\Imagine;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterConfiguration;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Site\BaseBundle\Entity\Word;
use Site\BaseBundle\Manager\Exception\WrongImageUrlException;
use Symfony\Component\DependencyInjection\Container;

use Imagine\Filter\Basic\Crop;
use Imagine\Filter\Basic\Thumbnail;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageManager
{
  protected $container;
  protected $crop_filter_name;
  /** @var FilterManager $filter_manager */
  protected $filter_manager;
  /** @var DataManager $data_manager */
  protected $data_manager;
  /** @var CacheManager $cache_manager */
  protected $cache_manager;
  /** @var UploaderHelper $uploader */
  protected $uploader;
  /** @var EntityManager */
  protected $em;

  public function __construct(Container $container, $crop_filter_name)
  {
    $this->container        = $container;
    $this->crop_filter_name = $crop_filter_name;
    $this->filter_manager   = $container->get('liip_imagine.filter.manager');
    $this->data_manager     = $container->get('liip_imagine.data.manager');
    $this->cache_manager    = $container->get('liip_imagine.cache.manager');
    $this->uploader         = $container->get('vich_uploader.templating.helper.uploader_helper');
    $this->em               = $container->get('doctrine')->getManager();
  }

  public function crop_image(Word $word, array $crop_data)
  {
    /** @var FilterConfiguration $filterConfig */
    $filterConfig = $this->filter_manager->getFilterConfiguration();
    $old_filename = $this->uploader->asset($word, 'imagefile');

    $image = $this->data_manager->find($this->crop_filter_name, $old_filename);

    $config                             = $filterConfig->get($this->crop_filter_name);
    $config['filters']['crop']['start'] = array($crop_data['crop_x'], $crop_data['crop_y']);
    $config['filters']['crop']['size']  = array($crop_data['crop_w'], $crop_data['crop_h']);
    $filterConfig->set($this->crop_filter_name, $config);

    $image_data     = $this->filter_manager->applyFilter($image, $this->crop_filter_name)->get('png');
    $temp_file_name = tempnam(sys_get_temp_dir(), uniqid() . '.png');
    $fh             = fopen($temp_file_name, 'wb');
    fwrite($fh, $image_data);
    fclose($fh);

    $uploaded_file = new UploadedFile($temp_file_name, uniqid() . '.png', 'image/png', filesize($temp_file_name));
    $word->setImagefile($uploaded_file);

    $this->em->persist($word);
    $this->em->flush();

    // cleanup file cache
    foreach (array('word_thumbnail', 'word_edit_source') as $filter) {
      $this->cache_manager->remove($old_filename, $filter);
    }
    // unlink temp file
    unlink($temp_file_name);
  }

  public function upload_from_url(Word $word, $url)
  {
    $image_data = file_get_contents($url);
    if (!$image_data) throw new WrongImageUrlException();

    $extension = pathinfo($url, PATHINFO_EXTENSION);

    $temp_file_name = tempnam(sys_get_temp_dir(), uniqid() . '.' . $extension);
    $fh             = fopen($temp_file_name, 'wb');
    fwrite($fh, $image_data);
    fclose($fh);

    $uploaded_file = new UploadedFile($temp_file_name, uniqid(). '.jpg', null, filesize($temp_file_name));
    $word->setImagefile($uploaded_file);

    /** @var Validator $validator  */
    $validator = $this->container->get('validator');
    $errors = $validator->validate($word);
    if ($errors->count())
    {
      unlink($temp_file_name);
      throw new WrongImageUrlException();
    }

    $this->em->persist($word);
    $this->em->flush($word);

    unlink($temp_file_name);
  }
}