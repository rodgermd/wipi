<?php

namespace Site\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Theme
 *
 * @ORM\Table(name="themes")
 * @ORM\Entity(repositoryClass="Site\BaseBundle\Entity\ThemeRepository")
 */
class Theme
{
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=100)
   */
  private $name;

  /**
   * @var string $slug
   * @Gedmo\Slug(handlers={
   * @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
   * @Gedmo\SlugHandlerOption(name="relationField", value="user"),
   * @Gedmo\SlugHandlerOption(name="relationSlugField", value="slug"),
   *      }),
   * @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\InversedRelativeSlugHandler", options={
   * @Gedmo\SlugHandlerOption(name="relationClass", value="Site\BaseBundle\Entity\Word"),
   * @Gedmo\SlugHandlerOption(name="mappedBy", value="theme"),
   * @Gedmo\SlugHandlerOption(name="inverseSlugField", value="slug")
   *      })
   * },
   * fields={"name"}
   * )
   * @ORM\Column(name="slug", type="string", length=255, unique=true)
   */
  private $slug;

  /**
   * @var \DateTime
   * @Gedmo\Timestampable(on="create")
   * @ORM\Column(name="created_at", type="datetime")
   */
  private $created_at;

  /**
   * @var \DateTime
   * @Gedmo\Timestampable(on="update")
   * @ORM\Column(name="updated_at", type="datetime")
   */
  private $updated_at;

  /**
   * @var User $user
   * @ORM\ManyToOne(targetEntity="Site\BaseBundle\Entity\User", inversedBy="themes")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
   * @Assert\NotBlank()
   */
  private $user;

  /**
   * @ORM\OneToMany(targetEntity="Site\BaseBundle\Entity\Word", mappedBy="theme", cascade={"persist", "remove"})
   * @var array $words
   */
  private $words;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->words = new ArrayCollection();
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
   * Set user
   *
   * @param User $user
   * @return Theme
   */
  public function setUser(User $user)
  {
    $this->user = $user;

    return $this;
  }

  /**
   * Get user
   *
   * @return User
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * Set name
   *
   * @param string $name
   * @return Theme
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get name
   *
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set slug
   *
   * @param string $slug
   * @return Theme
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
   * Get created_at
   *
   * @return \DateTime
   */
  public function getCreatedAt()
  {
    return $this->created_at;
  }

  /**
   * Get updated_at
   *
   * @return \DateTime
   */
  public function getUpdatedAt()
  {
    return $this->updated_at;
  }


  /**
   * Set updated_at
   *
   * @param \DateTime $updatedAt
   * @return Theme
   */
  public function setUpdatedAt($updatedAt)
  {
    $this->updated_at = $updatedAt;

    return $this;
  }

  /**
   * Add word
   *
   * @param \Site\BaseBundle\Entity\Word $word
   * @return Theme
   */
  public function addWord(Word $word)
  {
    $this->words[] = $word;

    return $this;
  }

  /**
   * Remove word
   *
   * @param \Site\BaseBundle\Entity\Word $word
   */
  public function removeWord(Word $word)
  {
    $this->words->removeElement($word);
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