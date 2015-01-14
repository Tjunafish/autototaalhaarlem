<?php

namespace Autoplan\DBBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * NewsletterGroup
 *
 * @ORM\Table(name="newsletter_group")
 * @ORM\Entity
 */
class NewsletterGroup
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\ManyToMany(targetEntity="NewsletterSubscription")
     * @ORM\JoinTable(name="newsletter_subscriptions_groups",
     *      joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="subscription_id", referencedColumnName="id")}
     *      )
     **/
    protected $subscribers;

    /**
     * How the title should look like
     *
     * @return string
     */
    public function __toString() {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     * @return NewsletterGroup
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
     * Set created
     *
     * @param \DateTime $created
     * @return NewsletterGroup
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
     * @param \Autoplan\DBBundle\Entity\NewsletterSubscription $subscribers
     * @return \Autoplan\DBBundle\Entity\NewsletterSubscription
     */
    public function setSubscribers($subscribers)
    {
        $this->subscribers = $subscribers;

        return $this->subscribers;
    }

    /**
     * @return \Autoplan\DBBundle\Entity\NewsletterSubscription
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }
}
