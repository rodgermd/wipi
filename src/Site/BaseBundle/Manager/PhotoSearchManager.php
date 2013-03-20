<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 20.03.13
 * Time: 22:49
 * To change this template use File | Settings | File Templates.
 */

namespace Site\BaseBundle\Manager;

use Rodgermd\FlickrApi\Model\SearchPhotoResult;
use Rodgermd\FlickrApi\Wrapper\FlickrApi;
use Rodgermd\FlickrApi\Wrapper\Curl as FlickrCurl;
use Symfony\Component\DependencyInjection\Container;

class PhotoSearchManager
{
  protected $api;

  public function __construct(Container $container)
  {
    $this->api = new FlickrApi(
      new FlickrCurl(),
      $container->getParameter('wipi_flickr_endpoint'),
      '',
      $container->getParameter('wipi_flickr_key')
    );
  }

  public function search($key)
  {
    $xml = $this->api->search($key);
    $photos_xml = $xml->photos;
    $attributes = $photos_xml->attributes();

    $result = array();

    if (!(int)@$attributes['total']) return $result;

    foreach($photos_xml->children() as $photo_xml)
    {
      $result[] = new SearchPhotoResult($photo_xml->attributes());
    }

    return $result;
  }
}