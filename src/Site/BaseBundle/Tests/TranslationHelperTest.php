<?php

namespace Site\BaseBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Site\BaseBundle\Helper\TranslationHelper;

class TranslationHelperTest extends WebTestCase
{
  protected $container;
  public function __construct($name = NULL, array $data = array(), $dataName = '')
  {
    parent::__construct($name, $data, $dataName);
    $client = static::createClient();

    $this->container = $client->getContainer();
  }

  public function testTranslateBing()
  {
    /** @var TranslationHelper $service  */
    $service = $this->container->get('wipi.translator');
    $this->assertEquals('Mug', $service->translate_bing('ru', 'en', 'Кружка'));

  }
}
