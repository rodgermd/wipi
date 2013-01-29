<?php

namespace Site\BaseBundle\Helper;
use Symfony\Component\DependencyInjection\Container;
use Site\BaseBundle\Helper\Exception\TranslationException;

class TranslationHelper
{
  protected $cached_results = array();
  protected $container;
  protected $bing_appid;

  public function __construct(Container $container)
  {
    $this->container  = $container;
    $this->bing_appid = $container->getParameter('bing_translate_app_id');
  }

  public function find($source_culture, $target_culture, $word)
  {
    if (@$this->cached_results[$source_culture][$target_culture][$word]) return $this->cached_results[$word];

    return $this->cached_results[$source_culture][$target_culture][$word] = $this->translate_bing($source_culture, $target_culture, $word);
  }

  /**
   * Translates using bing
   * @param $source_culture
   * @param $target_culture
   * @param $word
   * @return mixed
   * @throws Exception\TranslationException
   */
  public function translate_bing($source_culture, $target_culture, $word)
  {
    $url = "http://api.microsofttranslator.com/v2/ajax.svc/TranslateArray?appid=%appid%&from=\"%from%\"&to=\"%to%\"&texts=[\"%word%\"]";
    $url = strtr($url, array(
      '%appid%' => $this->bing_appid,
      '%from%'  => $source_culture,
      '%to%'    => $target_culture,
      '%word%'  => urlencode($word)
    ));

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
      $result = json_decode($response, true);
      preg_match('#TranslatedText":"(?P<match>[^\"]+)#', $response, $matches);
      if (@$matches['match']) {
        return $matches['match'];
      } else {
        throw new TranslationException('No translations');
      }
    }

    throw new TranslationException('Error received: ' . $response);
  }

}