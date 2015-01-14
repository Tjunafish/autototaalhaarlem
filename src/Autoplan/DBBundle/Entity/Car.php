<?php

namespace Autoplan\DBBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Car
 *
 * @ORM\Table(name="car")
 * @ORM\Entity()
 * @Vich\Uploadable
 */
class Car
{
    const SECTION_AUTOPLAN = 0;
    const SECTION_ASH_SPECIALS = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $section = 0;


    /**
     * @var CarBrand
     *
     * @ORM\ManyToOne(targetEntity="CarBrand")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="car_brand_id", referencedColumnName="id")
     * })
     */
    protected $carBrand;

    /**
     * @var CarBrand
     *
     * @ORM\ManyToOne(targetEntity="CarBrandModel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="car_brand_model_id", referencedColumnName="id")
     * })
     */
    protected $carBrandModel;

    /**
     * @var CarChassis
     *
     * @ORM\ManyToOne(targetEntity="CarChassis")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="car_chassis_id", referencedColumnName="id")
     * })
     */
    protected $carChassis;

    /**
     * @var CarFuel
     *
     * @ORM\ManyToOne(targetEntity="CarFuel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="car_fuel_id", referencedColumnName="id")
     * })
     */
    protected $carFuel;

    /**
     * @var string $slug
     *
     * @Gedmo\Slug(fields={"sortname"}, unique=false, updatable=true)
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    protected $slug;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $isOffer = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $hexonNr;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $carNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $sortname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $doors;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $kmcounter;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $kmcounterunit;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $transmission;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $transmissionCount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $taxtype;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $basecolor;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $paintType;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $constructionyear;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $monthlyprice;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $taxmin;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $taxmax;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $remarks;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $weight;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $maxtowing;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $cilinder;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $cilindercount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $motorpower;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $topspeed;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $energylabel;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $seats;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $bpmAmount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $fabric;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $fabricColor;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $firstImageUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $accessoires;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $seoTitle;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $seoDescription;

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

    /** 1:n */
    /**
     * @ORM\OneToMany(targetEntity="\Autoplan\DBBundle\Entity\CarPhoto", mappedBy="car", cascade={"persist"}, orphanRemoval=true, fetch="EAGER")
     * @ORM\OrderBy({"sequence" = "ASC"})
     * @var \Doctrine\Common\Collections\Collection $photos
     */
    protected $photos;

    public function __construct() {
        $this->photos = new ArrayCollection();
    }

    public function __toString() {
        return $this->getCarBrand() . " " . $this->getCarBrandModel() . " " . $this->getType();
    }

    public function getFirstPhoto() {
        $aImages = $this->getPhotos();
        if(sizeof($aImages) > 0) {
            return $aImages[0];
        } else {
            return null;
        }
    }

    public function getAccessoiresList() {
        return explode(',', $this->getAccessoires());
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
     * @return Page
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
     * Set seoTitle
     *
     * @param string $seoTitle
     * @return Page
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    /**
     * Get seoTitle
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * Set seoDescription
     *
     * @param string $seoDescription
     * @return Page
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * Get seoDescription
     *
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Page
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Page
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \Autoplan\DBBundle\Entity\CarBrand $carBrand
     */
    public function setCarBrand($carBrand)
    {
        $this->carBrand = $carBrand;
        return $this;
    }

    /**
     * @return \Autoplan\DBBundle\Entity\CarBrand
     */
    public function getCarBrand()
    {
        return $this->carBrand;
    }

    /**
     * @param \Autoplan\DBBundle\Entity\CarBrand $carBrandModel
     */
    public function setCarBrandModel($carBrandModel)
    {
        $this->carBrandModel = $carBrandModel;
        return $this;
    }

    /**
     * @return \Autoplan\DBBundle\Entity\CarBrand
     */
    public function getCarBrandModel()
    {
        return $this->carBrandModel;
    }

    /**
     * @param \Autoplan\DBBundle\Entity\CarChassis $carChassis
     */
    public function setCarChassis($carChassis)
    {
        $this->carChassis = $carChassis;
        return $this;
    }

    /**
     * @return \Autoplan\DBBundle\Entity\CarChassis
     */
    public function getCarChassis()
    {
        return $this->carChassis;
    }

    /**
     * @param \Autoplan\DBBundle\Entity\CarFuel $carFuel
     */
    public function setCarFuel($carFuel)
    {
        $this->carFuel = $carFuel;
        return $this;
    }

    /**
     * @return \Autoplan\DBBundle\Entity\CarFuel
     */
    public function getCarFuel()
    {
        return $this->carFuel;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $basecolor
     */
    public function setBasecolor($basecolor)
    {
        $this->basecolor = $basecolor;
        return $this;
    }

    /**
     * @return string
     */
    public function getBasecolor()
    {
        return $this->basecolor;
    }

    /**
     * @param string $bpmAmount
     */
    public function setBpmAmount($bpmAmount)
    {
        $this->bpmAmount = $bpmAmount;
        return $this;
    }

    /**
     * @return string
     */
    public function getBpmAmount()
    {
        return $this->bpmAmount;
    }

    /**
     * @param string $cilinder
     */
    public function setCilinder($cilinder)
    {
        $this->cilinder = $cilinder;
        return $this;
    }

    /**
     * @return string
     */
    public function getCilinder()
    {
        return $this->cilinder;
    }

    /**
     * @param string $cilindercount
     */
    public function setCilindercount($cilindercount)
    {
        $this->cilindercount = $cilindercount;
        return $this;
    }

    /**
     * @return string
     */
    public function getCilindercount()
    {
        return $this->cilindercount;
    }

    /**
     * @param string $constructionyear
     */
    public function setConstructionyear($constructionyear)
    {
        $this->constructionyear = $constructionyear;
        return $this;
    }

    /**
     * @return string
     */
    public function getConstructionyear()
    {
        return $this->constructionyear;
    }

    /**
     * @param string $doors
     */
    public function setDoors($doors)
    {
        $this->doors = $doors;
        return $this;
    }

    /**
     * @return string
     */
    public function getDoors()
    {
        return $this->doors;
    }

    /**
     * @param string $energylabel
     */
    public function setEnergylabel($energylabel)
    {
        $this->energylabel = $energylabel;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnergylabel()
    {
        return $this->energylabel;
    }

    /**
     * @param string $fabric
     */
    public function setFabric($fabric)
    {
        $this->fabric = $fabric;
        return $this;
    }

    /**
     * @return string
     */
    public function getFabric()
    {
        return $this->fabric;
    }

    /**
     * @param string $kmcounter
     */
    public function setKmcounter($kmcounter)
    {
        $this->kmcounter = $kmcounter;
        return $this;
    }

    /**
     * @return string
     */
    public function getKmcounter()
    {
        return $this->kmcounter;
    }

    /**
     * @param string $kmcounterunit
     */
    public function setKmcounterunit($kmcounterunit)
    {
        $this->kmcounterunit = $kmcounterunit;
        return $this;
    }

    /**
     * @return string
     */
    public function getKmcounterunit()
    {
        return $this->kmcounterunit;
    }

    /**
     * @param string $maxtowing
     */
    public function setMaxtowing($maxtowing)
    {
        $this->maxtowing = $maxtowing;
        return $this;
    }

    /**
     * @return string
     */
    public function getMaxtowing()
    {
        return $this->maxtowing;
    }

    /**
     * @param string $motorpower
     */
    public function setMotorpower($motorpower)
    {
        $this->motorpower = $motorpower;
        return $this;
    }

    /**
     * @return string
     */
    public function getMotorpower()
    {
        return $this->motorpower;
    }

    /**
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $remarks
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
        return $this;
    }

    /**
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * @param string $seats
     */
    public function setSeats($seats)
    {
        $this->seats = $seats;
        return $this;
    }

    /**
     * @return string
     */
    public function getSeats()
    {
        return $this->seats;
    }

    /**
     * @param string $sortname
     */
    public function setSortname($sortname)
    {
        $this->sortname = $sortname;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortname()
    {
        return $this->sortname;
    }

    /**
     * @param string $taxmax
     */
    public function setTaxmax($taxmax)
    {
        $this->taxmax = $taxmax;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxmax()
    {
        return $this->taxmax;
    }

    /**
     * @param string $taxmin
     */
    public function setTaxmin($taxmin)
    {
        $this->taxmin = $taxmin;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxmin()
    {
        return $this->taxmin;
    }

    /**
     * @param string $taxtype
     */
    public function setTaxtype($taxtype)
    {
        $this->taxtype = $taxtype;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxtype()
    {
        return $this->taxtype;
    }

    /**
     * @param string $topspeed
     */
    public function setTopspeed($topspeed)
    {
        $this->topspeed = $topspeed;
        return $this;
    }

    /**
     * @return string
     */
    public function getTopspeed()
    {
        return $this->topspeed;
    }

    /**
     * @param string $transmission
     */
    public function setTransmission($transmission)
    {
        $this->transmission = $transmission;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransmission()
    {
        return $this->transmission;
    }

    /**
     * @param string $transmissionCount
     */
    public function setTransmissionCount($transmissionCount)
    {
        $this->transmissionCount = $transmissionCount;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransmissionCount()
    {
        return $this->transmissionCount;
    }

    /**
     * @param string $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param string $carNumber
     */
    public function setCarNumber($carNumber)
    {
        $this->carNumber = $carNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getCarNumber()
    {
        return $this->carNumber;
    }

    /**
     * @param string $hexonNr
     */
    public function setHexonNr($hexonNr)
    {
        $this->hexonNr = $hexonNr;
        return $this;
    }

    /**
     * @return string
     */
    public function getHexonNr()
    {
        return $this->hexonNr;
    }


    /**
     * Add photo
     *
     * @param \Autoplan\DBBundle\Entity\CarPhoto $photo
     * @return Car
     */
    public function addPhoto(\Autoplan\DBBundle\Entity\CarPhoto $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \Autoplan\DBBundle\Entity\CarPhoto $photo
     */
    public function removeImage(\Autoplan\DBBundle\Entity\CarPhoto $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param string $firstImageUrl
     */
    public function setFirstImageUrl($firstImageUrl)
    {
        $this->firstImageUrl = $firstImageUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstImageUrl()
    {
        return $this->firstImageUrl;
    }

    /**
     * @param string $section
     */
    public function setSection($section)
    {
        $this->section = $section;
        return $this;
    }

    /**
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param boolean $isOffer
     */
    public function setIsOffer($isOffer)
    {
        $this->isOffer = $isOffer;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsOffer()
    {
        return $this->isOffer;
    }

    /**
     * @param string $paintType
     */
    public function setPaintType($paintType)
    {
        $this->paintType = $paintType;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaintType()
    {
        return $this->paintType;
    }

    /**
     * @param string $fabricColor
     */
    public function setFabricColor($fabricColor)
    {
        $this->fabricColor = $fabricColor;
        return $this;
    }

    /**
     * @return string
     */
    public function getFabricColor()
    {
        return $this->fabricColor;
    }

    /**
     * @param string $accessoires
     */
    public function setAccessoires($accessoires)
    {
        $this->accessoires = $accessoires;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessoires()
    {
        return $this->accessoires;
    }

    /**
     * @return string
     */
    public function getMonthlyprice()
    {
        return $this->monthlyprice;
    }

    /**
     * @param string $monthlyprice
     */
    public function setMonthlyprice($monthlyprice)
    {
        $this->monthlyprice = $monthlyprice;
        return $this;
    }




}
