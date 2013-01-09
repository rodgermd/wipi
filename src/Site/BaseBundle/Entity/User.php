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
   * @Gedmo\SlugHandlerOption(name="relationClass", value="Site\BaseBundle\Entity\Category"),
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
   * @ORM\OneToMany(targetEntity="Site\BaseBundle\Entity\Category", mappedBy="user", cascade={"persist", "remove"})
   * @var array $categories
   */
  protected $categories;

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->categories = new ArrayCollection();
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
   * Add category
   *
   * @param \Site\BaseBundle\Entity\Category $categories
   * @return User
   */
  public function addCategory(Category $categories)
  {
    $this->categories[] = $categories;

    return $this;
  }

  /**
   * Remove category
   *
   * @param \Site\BaseBundle\Entity\Category $categories
   */
  public function removeCategory(Category $categories)
  {
    $this->categories->removeElement($categories);
  }

  /**
   * Get categories
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getCategories()
  {
    return $this->categories;
  }

    /**
     * Add categories
     *
     * @param \Site\BaseBundle\Entity\Category $categories
     * @return User
     */
    public function addCategorie(\Site\BaseBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Site\BaseBundle\Entity\Category $categories
     */
    public function removeCategorie(\Site\BaseBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }
}