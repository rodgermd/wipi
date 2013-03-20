<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 20.03.13
 * Time: 23:16
 * To change this template use File | Settings | File Templates.
 */

namespace Site\BaseBundle\Tests;

use Site\BaseBundle\Manager\PhotoSearchManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhotoSearchManagerTest extends WebTestCase
{
  /** @var \Symfony\Component\DependencyInjection\ContainerInterface $container */
  protected $container;

  /** @var PhotoSearchManager $manager */
  protected $manager;

  public function __construct($name = NULL, array $data = array(), $dataName = '')
  {
    parent::__construct($name, $data, $dataName);
    $client = static::createClient();

    $this->container = $client->getContainer();
    $this->manager   = $this->container->get('wipi.manager.photo_search');
  }

  public function testSearch()
  {
    $results = $this->manager->search('кружка');
    $this->assertCount(100, $results);
  }
}