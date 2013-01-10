<?php

namespace Site\BaseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Site\BaseBundle\Entity\User;
use Site\BaseBundle\Entity\WordGroup;

class LoadWordGroupsData extends AbstractFixture implements OrderedFixtureInterface
{
  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager)
  {
    $category = new WordGroup();
    $category->setName('test group');
    $category->setUser($this->getReference('user.admin'));

    $this->setReference('wordgroup.test_group', $category);

    $manager->persist($category);
    $manager->flush();
  }

  /**
   * Get the order of this fixture
   *
   * @return integer
   */
  function getOrder()
  {
    return 2;
  }
}