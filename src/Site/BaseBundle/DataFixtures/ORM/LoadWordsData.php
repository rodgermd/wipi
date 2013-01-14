<?php

namespace Site\BaseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Site\BaseBundle\Entity\User;
use Site\BaseBundle\Entity\Word;

class LoadWordsData extends AbstractFixture implements OrderedFixtureInterface
{
  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager)
  {
    $word = new Word();
    $word->setSource('кружка');
    $word->setTarget('cup');
    $word->setSourceCulture('ru');
    $word->setTargetCulture('en');
    $word->setTheme($this->getReference('theme.test_theme'));

    $manager->persist($word);
    $manager->flush();
  }

  /**
   * Get the order of this fixture
   *
   * @return integer
   */
  function getOrder()
  {
    return 3;
  }
}