<?php

namespace Site\BaseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Site\BaseBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager)
  {
    $userAdmin = new User();
    $userAdmin->setUsername('admin');
    $userAdmin->setPlainPassword('admin');
    $userAdmin->setEnabled(true);
    $userAdmin->setEmail('rodger@ladela.com');
    $userAdmin->addRole('ROLE_ADMIN');

    $this->addReference('user.admin', $userAdmin);

    $manager->persist($userAdmin);
    $manager->flush();
  }

  /**
   * Get the order of this fixture
   *
   * @return integer
   */
  function getOrder()
  {
    return 1;
  }
}