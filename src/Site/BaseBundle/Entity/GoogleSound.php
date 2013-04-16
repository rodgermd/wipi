<?php

namespace Site\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * GoogleSound
 *
 * @ORM\Table(name="sounds__google")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class GoogleSound
{

  /**
   * @var string
   * @ORM\Id
   * @ORM\Column(name="culture", type="string", length=2)
   */
  private $culture;

  /**
   * @var string
   * @ORM\Id
   * @ORM\Column(name="word", type="string", length=100)
   */
  private $word;

  /**
   * @var string
   *
   * @ORM\Column(name="filename", type="string", length=52)
   */
  private $filename;

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
   * @Assert\File(maxSize="3M", mimeTypes={"audio/mpeg"})
   * @Assert\NotNull()
   * @Vich\UploadableField(mapping="google_sound", fileNameProperty="filename")
   * @var UploadedFile $imagefile
   */
  private $file;


  /**
   * Set culture
   *
   * @param string $culture
   * @return GoogleSound
   */
  public function setCulture($culture)
  {
    $this->culture = $culture;

    return $this;
  }

  /**
   * Get culture
   *
   * @return string
   */
  public function getCulture()
  {
    return $this->culture;
  }

  /**
   * Set word
   *
   * @param string $word
   * @return GoogleSound
   */
  public function setWord($word)
  {
    $this->word = $word;

    return $this;
  }

  /**
   * Get word
   *
   * @return string
   */
  public function getWord()
  {
    return $this->word;
  }

  /**
   * Set filename
   *
   * @param string $filename
   * @return GoogleSound
   */
  public function setFilename($filename)
  {
    $this->filename = $filename;

    return $this;
  }

  /**
   * Get filename
   *
   * @return string
   */
  public function getFilename()
  {
    return $this->filename;
  }

  /**
   * Set createdAt
   *
   * @param \DateTime $createdAt
   * @return GoogleSound
   */
  public function setCreatedAt($createdAt)
  {
    $this->created_at = $createdAt;

    return $this;
  }

  /**
   * Get createdAt
   *
   * @return \DateTime
   */
  public function getCreatedAt()
  {
    return $this->created_at;
  }

  /**
   * Sets updated_at
   * @param \DateTime $updated_at
   * @return $this
   */
  public function setUpdatedAt($updated_at)
  {
    $this->updated_at = $updated_at;
    return $this;
  }

  /**
   * Gets updated_at
   * @return \DateTime
   */
  public function getUpdatedAt()
  {
    return $this->updated_at;
  }

  /**
   * Sets file
   * @param UploadedFile $file
   */
  public function setFile(UploadedFile $file)
  {
    $this->updated_at = new \DateTime('-1 second');
    $this->file       = $file;
  }
}
