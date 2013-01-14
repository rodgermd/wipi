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
   * @var array $word_groups
   */
  protected $themes;

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->themes = new ArrayCollection();
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
}