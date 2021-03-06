<?php

namespace Site\BaseBundle\Helper;
use Symfony\Component\DependencyInjection\Container;
use Site\BaseBundle\Helper\Exception\TranslationException;

class TranslationHelper
{
  protected $container;
  protected $translator_params;

  const GOOGLE         = 'google';
  const YANDEX         = 'yandex';
  const GOOGLE_SUGGEST = 'google_suggest';

  public static $translators = array(self::GOOGLE, self::YANDEX, self::GOOGLE_SUGGEST);

  public function __construct(Container $container)
  {
    $this->container         = $container;
    $this->translator_params = $container->getParameter('provider_keys');
  }

  /**
   * Finds word translation
   * @param $source_culture
   * @param $target_culture
   * @param $word
   * @return array
   */
  public function find($source_culture, $target_culture, $word)
  {
    $word = mb_strtolower($word, 'UTF8');

    $result = array();
    try {
      $result[self::GOOGLE] = $this->translate_google($source_culture, $target_culture, $word);
    } catch (TranslationException $e) {
    }
    try {
      $result[self::YANDEX] = $this->translate_yandex($source_culture, $target_culture, $word);
    } catch (TranslationException $e) {
    }
    try {
      $result[self::GOOGLE_SUGGEST] = $this->translate_google_suggest($source_culture, $target_culture, $word);
    } catch (TranslationException $e) {
    }

    return $result;
  }

  /**
   * Finds word translations and groups them by translated word
   * @param $source_culture
   * @param $target_culture
   * @param $word
   * @return array
   */
  public function find_group_by_word($source_culture, $target_culture, $word)
  {
    $found  = $this->find($source_culture, $target_culture, $word);
    $result = array();
    foreach ($found as $service => $values) {
      foreach ($values as $word) {
        @$result[strtolower($word)][] = $service;
      }
    }
    return $result;
  }

  /**
   * Translates using google
   * @param $source_culture
   * @param $target_culture
   * @param $word
   * @return mixed
   * @throws Exception\TranslationException
   */
  public function translate_google($source_culture, $target_culture, $word)
  {
    $url = strtr("https://www.googleapis.com/language/translate/v2?key=%api_key%&q=%word%&source=%from%&target=%to%", array(
      '%api_key%' => $this->translator_params['google']['api_key'],
      '%from%'    => $source_culture,
      '%to%'      => $target_culture,
      '%word%'    => urlencode($word)
    ));

    $result = $this->query($url);

    $parsed = array_filter(array_map(function ($e) {
      return trim($e['translatedText']);
    }, $result['data']['translations']));
    if (!count($parsed)) throw new TranslationException('No results');

    return $parsed;
  }

  /**
   * Translates using google
   * @param $source_culture
   * @param $target_culture
   * @param $word
   * @return mixed
   * @throws Exception\TranslationException
   */
  public function translate_google_suggest($source_culture, $target_culture, $word)
  {
    $url = strtr("http://translate.google.com/translate_a/t?client=t&text=%word%&hl=en&sl=%from%&tl=%to%&ie=UTF-8&oe=UTF-8&multires=1&otf=2&ssel=0&tsel=0&sc=1",
      array(
      '%from%'    => $source_culture,
      '%to%'      => $target_culture,
      '%word%'    => urlencode($word)
    ));

    $result = $this->query($url);
    if (!$result)throw new TranslationException('No results');

    $parsed = new GoogleTransResponse($result);

    return $parsed;
  }

  /**
   * Translates using yandex
   * @param $source_culture
   * @param $target_culture
   * @param $word
   * @return array
   * @throws Exception\TranslationException
   */
  public function translate_yandex($source_culture, $target_culture, $word)
  {
    $url    = "http://translate.yandex.net/api/v1/tr.json/translate";
    $result = $this->query($url, array(
      CURLOPT_POST       => true,
      CURLOPT_POSTFIELDS => array(
        'text'   => $word,
        'lang'   => strtr("%from%-%to%", array('%from%' => $source_culture, '%to%' => $target_culture)),
        'format' => 'json')));
    $parsed = array_filter(array_map('trim', $result['text']));

    if (!count($parsed)) throw new TranslationException('No results');

    return $parsed;
  }

  protected function query($url, array $options = array())
  {
    $ch      = curl_init();
    $options = array(
      CURLOPT_URL            => $url,
      CURLOPT_RETURNTRANSFER => TRUE
    ) + $options;
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode != 200) throw new TranslationException('Error received: ' . $response);
    $response = preg_replace("#,\s*,#", ",", $response);
    $response = preg_replace("#,\s*,#", ",", $response);
    return json_decode($response, true);
  }

}