<?php

namespace Site\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Word
 *
 * @ORM\Table(name="words")
 * @ORM\Entity(repositoryClass="Site\BaseBundle\Entity\WordRepository")
 */
class Word
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
   * @Assert\NotBlank
   * @ORM\Column(name="source_culture", type="string", length=2)
   */
  private $source_culture;

  /**
   * @var string
   * @Assert\NotBlank
   * @ORM\Column(name="target_culture", type="string", length=2)
   */
  private $target_culture;

  /**
   * @var string
   *
   * @ORM\Column(name="source", type="string", length=100)
   * @Assert\NotBlank
   */
  private $source;

  /**
   * @var string
   *
   * @ORM\Column(name="target", type="string", length=100)
   * @Assert\NotBlank
   */
  private $target;

  /**
   * @var string
   *
   * @ORM\Column(name="image_filename", type="string", length=50, nullable=true)
   */
  private $image_filename;

  /**
   * @var string
   *
   * @ORM\Column(name="sound_filename", type="string", length=50, nullable=true)
   */
  private $sound_filename;

  /**
   * @var string $slug
   * @Gedmo\Slug(handlers={
   * @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
   * @Gedmo\SlugHandlerOption(name="relationField", value="word_group"),
   * @Gedmo\SlugHandlerOption(name="relationSlugField", value="slug"),
   *      })
   * },
   * fields={"source", "target"}
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
   * @var WordGroup $category
   * @ORM\ManyToOne(targetEntity="Site\BaseBundle\Entity\WordGroup", inversedBy="words")
   * @ORM\JoinColumn(name="word_group_id", referencedColumnName="id", onDelete="SET NULL")
   * @Assert\NotBlank()
   */
  private $word_group;


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
   * Set source_culture
   *
   * @param string $sourceCulture
   * @return Word
   */
  public function setSourceCulture($sourceCulture)
  {
    $this->source_culture = $sourceCulture;

    return $this;
  }

  /**
   * Get source_culture
   *
   * @return string
   */
  public function getSourceCulture()
  {
    return $this->source_culture;
  }

  /**
   * Set target_culture
   *
   * @param string $targetCulture
   * @return Word
   */
  public function setTargetCulture($targetCulture)
  {
    $this->target_culture = $targetCulture;

    return $this;
  }

  /**
   * Get target_culture
   *
   * @return string
   */
  public function getTargetCulture()
  {
    return $this->target_culture;
  }

  /**
   * Set source
   *
   * @param string $source
   * @return Word
   */
  public function setSource($source)
  {
    $this->source = $source;

    return $this;
  }

  /**
   * Get source
   *
   * @return string
   */
  public function getSource()
  {
    return $this->source;
  }

  /**
   * Set target
   *
   * @param string $target
   * @return Word
   */
  public function setTarget($target)
  {
    $this->target = $target;

    return $this;
  }

  /**
   * Get target
   *
   * @return string
   */
  public function getTarget()
  {
    return $this->target;
  }

  /**
   * Set image_filename
   *
   * @param string $imageFilename
   * @return Word
   */
  public function setImageFilename($imageFilename)
  {
    $this->image_filename = $imageFilename;

    return $this;
  }

  /**
   * Get image_filename
   *
   * @return string
   */
  public function getImageFilename()
  {
    return $this->image_filename;
  }

  /**
   * Set sound_filename
   *
   * @param string $soundFilename
   * @return Word
   */
  public function setSoundFilename($soundFilename)
  {
    $this->sound_filename = $soundFilename;

    return $this;
  }

  /**
   * Get sound_filename
   *
   * @return string
   */
  public function getSoundFilename()
  {
    return $this->sound_filename;
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
   * Set updated_at
   *
   * @param \DateTime $updatedAt
   * @return Word
   */
  public function setUpdatedAt($updatedAt)
  {
    $this->updated_at = $updatedAt;

    return $this;
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
     * Set slug
     *
     * @param string $slug
     * @return Word
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
     * Set category
     *
     * @param \Site\BaseBundle\Entity\WordGroup $category
     * @return Word
     */
    public function setWordgroup(WordGroup $category = null)
    {
        $this->word_group = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Site\BaseBundle\Entity\WordGroup
     */
    public function getWordgroup()
    {
        return $this->word_group;
    }
}