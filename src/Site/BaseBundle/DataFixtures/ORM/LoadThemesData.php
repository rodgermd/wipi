<?php

namespace Site\BaseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Site\BaseBundle\Entity\User;
use Site\BaseBundle\Entity\Theme;

class LoadThemesData extends AbstractFixture implements OrderedFixtureInterface
{
  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager)
  {
    $category = new Theme();
    $category->setName('test theme');
    $category->setUser($this->getReference('user.admin'));

    $this->setReference('theme.test_theme', $category);

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