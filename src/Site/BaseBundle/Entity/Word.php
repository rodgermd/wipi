<?php

namespace Site\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Word
 *
 * @ORM\Table(name="words")
 * @ORM\Entity(repositoryClass="Site\BaseBundle\Entity\WordRepository")
 * @Vich\Uploadable
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
   *
   * @ORM\Column(name="source", type="string", length=100)
   * @Assert\NotBlank(groups={"Default", "step1"})
   */
  private $source;

  /**
   * @var string
   *
   * @ORM\Column(name="target", type="string", length=100)
   * @Assert\NotBlank(groups={"Default", "step1"})
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
   * @Gedmo\SlugHandlerOption(name="relationField", value="theme"),
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
   * @var Theme $theme
   * @ORM\ManyToOne(targetEntity="Site\BaseBundle\Entity\Theme", inversedBy="words")
   * @ORM\JoinColumn(name="theme_id", referencedColumnName="id", onDelete="SET NULL")
   * @Assert\NotBlank(groups={"Default", "step1"})
   */
  private $theme;

  /**
   * @var User $user
   * @ORM\ManyToOne(targetEntity="Site\BaseBundle\Entity\User", inversedBy="words")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
   * @Assert\NotBlank()
   */
  private $user;

  /**
   * @Assert\File(maxSize="3M", mimeTypes={"image/png", "image/jpeg", "image/pjpeg"})
   * @Vich\UploadableField(mapping="word_image", fileNameProperty="image_filename")
   * @var UploadedFile $imagefile
   */
  private $imagefile;

  /**
   * @Assert\File(maxSize="5M", mimeTypes={"audio/mpeg", "audio/wav"})
   * @Vich\UploadableField(mapping="word_sound", fileNameProperty="sound_filename")
   * @var UploadedFile $soundfile
   */
  private $soundfile;


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
   * Set source
   *
   * @param string $source
   * @return Word
   */
  public function setSource($source)
  {
    $this->source = mb_strtolower($source);

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
    $this->target = mb_strtolower($target);

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
   * Set theme
   *
   * @param \Site\BaseBundle\Entity\Theme $theme
   * @return Word
   */
  public function setTheme(Theme $theme = null)
  {
    $this->theme = $theme;

    return $this;
  }

  /**
   * Get theme
   *
   * @return \Site\BaseBundle\Entity\Theme
   */
  public function getTheme()
  {
    return $this->theme;
  }

  /**
   * Set created_at
   *
   * @param \DateTime $createdAt
   * @return Word
   */
  public function setCreatedAt($createdAt)
  {
    $this->created_at = $createdAt;

    return $this;
  }

  /**
   * Set user
   *
   * @param \Site\BaseBundle\Entity\User $user
   * @return Word
   */
  public function setUser(\Site\BaseBundle\Entity\User $user = null)
  {
    $this->user = $user;

    return $this;
  }

  /**
   * Get user
   *
   * @return \Site\BaseBundle\Entity\User
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * Sets Imagefile
   * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
   */
  public function setImagefile(UploadedFile $file)
  {
    $this->imagefile = $file;
  }

  /**
   * Gets uploaded file
   * @return \Symfony\Component\HttpFoundation\File\UploadedFile
   */
  public function getImagefile()
  {
    return $this->imagefile;
  }

  /**
   * Sets sound file
   * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
   */
  public function setSoundfile(UploadedFile $file)
  {
    $this->soundfile = $file;
  }

  /**
   * Gets uploaded sound file
   * @return \Symfony\Component\HttpFoundation\File\UploadedFile
   */
  public function getSoundfile()
  {
    return $this->soundfile;
  }
}