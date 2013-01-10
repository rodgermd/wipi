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
   * @ORM\OneToMany(targetEntity="Site\BaseBundle\Entity\WordGroup", mappedBy="user", cascade={"persist", "remove"})
   * @var array $word_groups
   */
  protected $word_groups;

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->word_groups = new ArrayCollection();
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
   * Get word groups
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getWordGroups()
  {
    return $this->word_groups;
  }

    /**
     * Add word group
     *
     * @param \Site\BaseBundle\Entity\WordGroup $word_group
     * @return User
     */
    public function addWordGroup(WordGroup $word_group)
    {
        $this->word_groups[] = $word_group;
    
        return $this;
    }

    /**
     * Remove word group
     *
     * @param \Site\BaseBundle\Entity\WordGroup $word_group
     */
    public function removeWordGroup(WordGroup $word_group)
    {
        $this->word_groups->removeElement($word_group);
    }
}