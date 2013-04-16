<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 20.03.13
 * Time: 23:16
 * To change this template use File | Settings | File Templates.
 */

namespace Site\BaseBundle\Tests;

use Site\BaseBundle\Manager\GoogleSoundManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GoogleSoundManagerTest extends WebTestCase
{
  /** @var \Symfony\Component\DependencyInjection\ContainerInterface $container */
  protected $container;

  /** @var GoogleSoundManager $manager */
  protected $manager;

  public function __construct($name = NULL, array $data = array(), $dataName = '')
  {
    parent::__construct($name, $data, $dataName);
    $client = static::createClient();

    $this->container = $client->getContainer();
    $this->manager   = $this->container->get('wipi.manager.google_sound');
  }

  public function testManager()
  {
    $results = $this->manager->getSound('ru', 'кружка');

  }
}