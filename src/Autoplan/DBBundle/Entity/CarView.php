<?php

namespace Autoplan\DBBundle\Entity;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * CarView
 *
 * @ORM\Table(name="car_view")
 * @ORM\Entity()
 * @Vich\Uploadable
 */
class CarView
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
     *   @ORM\JoinColumn(name="car_id", referencedColumnName="id", onDelete="set null")
     * })
     */
    protected $car;

    /**
     * @var $clientIP
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $clientIP;

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

    /**
     * @param mixed $clientIP
     */
    public function setClientIP($clientIP)
    {
        $this->clientIP = $clientIP;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientIP()
    {
        return $this->clientIP;
    }



}