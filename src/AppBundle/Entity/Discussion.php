<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity
 * @ORM\Table(name="discussion")
 * @ORM\HasLifecycleCallbacks
 */
class Discussion
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Owner", inversedBy="createdDiscussions")
     * @ORM\JoinColumn(name="owner_id")
     */
    private $creator;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Owner", inversedBy="discussions")
     * @ORM\JoinColumn()
     */

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Owner")
     * @ORM\JoinTable(name="disscussion_members")
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="discussion")
     * @OrderBy({"date" = "ASC"})
     */
    private $messages;

    /**
     * @ORM\Column(type="text")
     */
    private $subject;

    /**
     * @var boolean
     */
    private $public;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $archived;

    /**
     * @return mixed
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param mixed $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return mixed
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param mixed $members
     */
    public function setMembers($members)
    {
        $this->members = $members;
    }


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
     * @return Discussion
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
     * Add member
     *
     * @param \AppBundle\Entity\Owner $member
     *
     * @return Discussion
     */
    public function addMember(\AppBundle\Entity\Owner $member)
    {
        $this->members[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param \AppBundle\Entity\Owner $member
     */
    public function removeMember(\AppBundle\Entity\Owner $member)
    {
        $this->members->removeElement($member);
    }

    /**
     * Add message
     *
     * @param \AppBundle\Entity\Message $message
     *
     * @return Discussion
     */
    public function addMessage(\AppBundle\Entity\Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \AppBundle\Entity\Message $message
     */
    public function removeMessage(\AppBundle\Entity\Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    public function __construct(){
        $this->members = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function __toString()
    {
        return "aa";
    }

    /**
     * @return mixed
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @param mixed $archived
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
    }


}
