<?php

namespace Site\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Site\BaseBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @Gedmo\Slug(handlers={
   * @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\InversedRelativeSlugHandler", options={
   * @Gedmo\SlugHandlerOption(name="relationClass", value="Site\BaseBundle\Entity\Theme"),
   * @Gedmo\SlugHandlerOption(name="mappedBy", value="user"),
   * @Gedmo\SlugHandlerOption(name="inverseSlugField", value="slug")
   *      })
   * },
   * fields={"username"}
   * )
   * @Doctrine\ORM\Mapping\Column(length=64, unique=true)
   */
  protected $slug;

  /**
   * @ORM\OneToMany(targetEntity="Site\BaseBundle\Entity\Theme", mappedBy="user", cascade={"persist", "remove"})
   * @var array $themes
   */
  protected $themes;

  /**
   * @ORM\OneToMany(targetEntity="Site\BaseBundle\Entity\Word", mappedBy="user", cascade={"persist", "remove"})
   * @var array $words
   */
  protected $words;

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->themes = new ArrayCollection();
    $this->words  = new ArrayCollection();
  }

  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set slug
   *
   * @param string $slug
   * @return User
   */
  public function setSlug($slug)
  {
    $this->slug = $slug;

    return $this;
  }

  /**
   * Get slug
   *
   * @return string
   */
  public function getSlug()
  {
    return $this->slug;
  }

  /**
   * Get themes
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getThemes()
  {
    return $this->themes;
  }

  /**
   * Add theme
   *
   * @param \Site\BaseBundle\Entity\Theme $theme
   * @return User
   */
  public function addTheme(Theme $theme)
  {
    $this->themes[] = $theme;

    return $this;
  }

  /**
   * Remove theme
   *
   * @param \Site\BaseBundle\Entity\Theme $theme
   */
  public function removeWordGroup(Theme $theme)
  {
    $this->themes->removeElement($theme);
  }

  /**
   * Remove themes
   *
   * @param \Site\BaseBundle\Entity\Theme $themes
   */
  public function removeTheme(\Site\BaseBundle\Entity\Theme $themes)
  {
    $this->themes->removeElement($themes);
  }

  /**
   * Add words
   *
   * @param \Site\BaseBundle\Entity\Word $words
   * @return User
   */
  public function addWord(\Site\BaseBundle\Entity\Word $words)
  {
    $this->words[] = $words;

    return $this;
  }

  /**
   * Remove words
   *
   * @param \Site\BaseBundle\Entity\Word $words
   */
  public function removeWord(\Site\BaseBundle\Entity\Word $words)
  {
    $this->words->removeElement($words);
  }

  /**
   * Get words
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getWords()
  {
    return $this->words;
  }
}