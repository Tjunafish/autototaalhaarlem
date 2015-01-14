<?php

namespace Autoplan\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * NewsletterSubscription
 *
 * @ORM\Table(name="newsletter_subscription")
 * @ORM\Entity()
 */
class NewsletterSubscription
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
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @var string $slug
     *
     * @Gedmo\Slug(fields={"email"}, unique=true, updatable=true)
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $email;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $active = false;

    /**
     * @var \datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $groups
     *
     * @ORM\ManyToMany(targetEntity="NewsletterGroup", mappedBy="subscribers")
     */
    protected $groups;

    /**
     * @var \datetime $updated
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;

    public function __toString() {
        return $this->getEmail();
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
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $active
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return $this->active;
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
     * @param \Autoplan\DBBundle\Entity\NewsletterGroup $groups
     * @return \Autoplan\DBBundle\Entity\NewsletterGroup
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this->groups;
    }

    /**
     * @return \Autoplan\DBBundle\Entity\NewsletterGroup
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Used when exporting to CSV
     *
     * @return [type]
     */
    public function toArray()
    {

        $groups = implode(', ', $this->groups->toArray());

        return array(
            $this->active ? 'Ja' : 'Nee',
            (empty($this->name) ? '-' : $this->name),
            $this->email,
            (empty($groups) ? '-' : $groups),
            $this->created->format('Y-m-d')
        );
    }

}
