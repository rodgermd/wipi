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
    $theme = new Theme();
    $theme->setName('test theme');
    $theme->setUser($this->getReference('user.admin'));
    $theme->setSourceCulture('ru');
    $theme->setTargetCulture('en');

    $this->setReference('theme.test_theme', $theme);

    $manager->persist($theme);
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