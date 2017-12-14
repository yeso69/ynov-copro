<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification")
 * @ORM\HasLifecycleCallbacks
 */
class Notification
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $subject;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Notification
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Notification
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Owner", mappedBy="notifications")
     */
    private $recipients;

    /**
     * @return mixed
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * @param mixed $recipients
     */
    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recipients = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add recipient
     *
     * @param \AppBundle\Entity\Owner $recipient
     *
     * @return Notification
     */
    public function addRecipient(\AppBundle\Entity\Owner $recipient)
    {
        $this->recipients[] = $recipient;

        return $this;
    }

    /**
     * Remove recipient
     *
     * @param \AppBundle\Entity\Owner $recipient
     */
    public function removeRecipient(\AppBundle\Entity\Owner $recipient)
    {
        $this->recipients->removeElement($recipient);
    }
}
