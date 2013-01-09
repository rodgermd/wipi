<?php

namespace Site\BaseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Site\BaseBundle\Entity\User;

class LoadUserData implements FixtureInterface
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

    $manager->persist($userAdmin);
    $manager->flush();
  }
}