<?php

namespace Site\BaseBundle\Tests;

use JMS\Serializer\Serializer;
use Site\BaseBundle\Helper\Exception\TranslationException;
use Site\BaseBundle\Helper\GoogleTransResponse;
use Site\BaseBundle\Helper\GoogleTransTranslation;
use Site\BaseBundle\Helper\GoogleTranslateResponse;
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
    /** @var TranslationHelper $service */
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
    } catch (\Exception $e) {
      $this->assertTrue($e instanceof \Site\BaseBundle\Helper\Exception\TranslationException);
    }

    try {
      $service->translate_google('en', 'ru', '');
      // should never get here
      $this->assertTrue(true == false);
    } catch (\Exception $e) {
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
    } catch (\Exception $e) {
      $this->assertTrue($e instanceof \Site\BaseBundle\Helper\Exception\TranslationException);
    }

    try {
      $response = $service->translate_yandex('en', 'ru', '');
      // should never get here
      $this->assertTrue(true == false);
    } catch (\Exception $e) {
      $this->assertTrue($e instanceof \Site\BaseBundle\Helper\Exception\TranslationException);
    }

    // try get helper response
    $result = $service->find('en', 'ru', 'Cup');
    $this->assertCount(2, $result);

    // check grouping
    $result = $service->find_group_by_word('ru', 'en', 'мир');
    $this->assertCount(1, $result);
    $this->assertArrayHasKey('world', $result);
    $this->assertCount(2, $result['world']);
    $this->assertCount(0, array_diff($result['world'], array('google', 'yandex')));
  }

  public function testTranslateGoogleSuggestion()
  {
    /** @var TranslationHelper $service */
    $service = $this->container->get('wipi.translator');
    try {
      $result = $service->translate_google_suggest('ru', 'en', 'кружка');
      $this->assertTrue($result instanceof GoogleTransResponse);

      /** @var Serializer $serializer */
      $serializer = $this->container->get('jms_serializer');

      $json = $serializer->serialize($result, 'json');
      $this->assertTrue(strlen($json) > 0);
    } catch (TranslationException $e) {
    }

    $this->assertCount(6, $result->translations);
    $this->assertCount(6, $result->translations[0]->reverse_values);
  }
}
