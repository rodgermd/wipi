<?php
namespace Site\BaseBundle\Twig;

use \Twig_SimpleFilter;
use \Twig_SimpleFunction;
use Rodgermd\SfToolsBundle\Twig\ImagesExtension;

use InvalidArgumentException;

use Site\BaseBundle\Entity\Word;

class ImageExtension extends ImagesExtension {
  public function getFilters()
  {
    return array(
      new Twig_SimpleFilter('word_thumbnail', array($this, 'word_thumbnail'), array('is_safe' => array('html')))
    );
  }

  /**
   * Render word thumbnail
   * @param \Site\BaseBundle\Entity\Word $word
   * @param string $template
   * @return string
   */
  public function word_thumbnail(Word $word, $template = 'word_thumbnail')
  {
    try {
      $filename = $this->uploader_helper->asset($word, 'imagefile');
    }
    catch(InvalidArgumentException $e){
      $filename = '/images/icons/add.png';
    }

    return $this->image_tag($this->thumbnails_helper->filter($filename, $template));
  }
  public function getName()
  {
    return 'wipi.images_extension';
  }

}