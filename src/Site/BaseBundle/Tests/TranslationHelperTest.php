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

  public function testTranslateGoogle()
  {
    /** @var TranslationHelper $service  */
    $service = $this->container->get('wipi.translator');

    // test google
    $result = $service->translate_google('ru', 'en', 'Кружка');
    $this->assertTrue(is_array($result));
    $this->assertCount(1, $result);
    $this->assertEquals('Mug', $result[0]);

    $result = $service->translate_google('en', 'ru', 'Mug');
    $this->assertTrue(is_array($result));
    $this->assertCount(1, $result);
    $this->assertEquals('Кружка', $result[0]);

    try {
      $service->translate_google('zz', 'ru', 'Mug');
      // should never get here
      $this->assertTrue(true == false);
    }
    catch (\Exception $e)
    {
      $this->assertTrue($e instanceof \Site\BaseBundle\Helper\Exception\TranslationException);
    }

    try {
      $service->translate_google('en', 'ru', '');
      // should never get here
      $this->assertTrue(true == false);
    }
    catch (\Exception $e)
    {
      $this->assertTrue($e instanceof \Site\BaseBundle\Helper\Exception\TranslationException);
    }

    // test yandex

    $result = $service->translate_yandex('en', 'ru', 'Mug');
    $this->assertTrue(is_array($result));
    $this->assertCount(1, $result);
    $this->assertEquals('Кружку', $result[0]);

    $result = $service->translate_yandex('ru', 'en', 'Кружка');
    $this->assertTrue(is_array($result));
    $this->assertCount(1, $result);
    $this->assertEquals('Circle', $result[0]);

    try {
      $service->translate_yandex('zz', 'ru', 'Mug');
      // should never get here
      $this->assertTrue(true == false);
    }
    catch (\Exception $e)
    {
      $this->assertTrue($e instanceof \Site\BaseBundle\Helper\Exception\TranslationException);
    }

    try {
      $response = $service->translate_yandex('en', 'ru', '');
      // should never get here
      $this->assertTrue(true == false);
    }
    catch (\Exception $e)
    {
      $this->assertTrue($e instanceof \Site\BaseBundle\Helper\Exception\TranslationException);
    }

    // try get helper response
    $result = $service->find('en', 'ru', 'Cup');
    $this->assertCount(2, $result);
  }
}
