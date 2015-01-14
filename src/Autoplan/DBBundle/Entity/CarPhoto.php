<?php

namespace Autoplan\DBBundle\Entity;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * CarPhoto
 *
 * @ORM\Table(name="car_photo")
 * @ORM\Entity()
 * @Vich\Uploadable
 */
class CarPhoto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Car
     *
     * @ORM\ManyToOne(targetEntity="Car")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="car_id", referencedColumnName="id")
     * })
     */
    protected $car;

    /**
     * @var integer
     *
     * @Gedmo\SortablePosition
     * @ORM\Column(name="sequence", type="integer")
     */
    protected $sequence;

    /**
     * @Vich\UploadableField(mapping="car_image", fileNameProperty="imagePath")
     */
    protected $image;

    /**
     * @var $imagePath
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $imagePath;

    /**
     * @var \datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @var \datetime $updated
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;


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
     * @param \Autoplan\DBBundle\Entity\Car $car
     */
    public function setCar($car)
    {
        $this->car = $car;
        return $this;
    }

    /**
     * @return \Autoplan\DBBundle\Entity\Car
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param \datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $imagePath
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * @param int $sequence
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
        return $this;
    }

    /**
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param \datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return \datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }



}