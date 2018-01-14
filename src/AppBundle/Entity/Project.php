<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
{

    const STATUS_IN_DISCUSSION = 'In discussion';
    const STATUS_WAINTING_FOR_EXECUTION = 'Waiting for execution';
    const STATUS_DONE = 'Done';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Survey", mappedBy="project")
     */
    private $surveys;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Note", mappedBy="project")
     */
    private $notes;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     *
     * @ORM\Column(name="openDate", type="datetime")
     */
    private $openDate;

    /**
     * @var datetime
     *
     * @ORM\Column(name="closeDate", type="datetime", nullable=true)
     */
    private $closeDate;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Owner", inversedBy="createdProjects")
     * @ORM\JoinColumn(name="owner_id")
     */
    private $creator;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Owner")
     * @ORM\JoinTable(name="project_members")
     */
    private $members;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Discussion", cascade={"persist","remove"})
     * @JoinColumn(name="discussion_id")
     */
    private $discussion;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $document;

    public function __construct(Owner $user){
        $this->members = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->openDate = new \DateTime('now');
        $this->creator = $user;
        $this->discussion = new Discussion($user);
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
     * Set surveys
     *
     * @param string $surveys
     *
     * @return Project
     */
    public function setSurveys($surveys)
    {
        $this->surveys = $surveys;

        return $this;
    }

    /**
     * Get surveys
     *
     * @return string
     */
    public function getSurveys()
    {
        return $this->surveys;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return Project
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->discussion->setSubject('Project: '.$name);
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
     * Set description
     *
     * @param string $description
     *
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        if (!in_array($status, array(self::STATUS_IN_DISCUSSION, self::STATUS_WAINTING_FOR_EXECUTION, self::STATUS_DONE))) {
            throw new \InvalidArgumentException("Invalid status");
        }
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set openDate
     *
     * @param datetime_immutable $openDate
     *
     * @return Project
     */
    public function setOpenDate($openDate)
    {
        $this->openDate = $openDate;

        return $this;
    }

    /**
     * Get openDate
     *
     * @return datetime_immutable
     */
    public function getOpenDate()
    {
        return $this->openDate;
    }

    /**
     * Set closeDate
     *
     * @param datetime_immutable $closeDate
     *
     * @return Project
     */
    public function setCloseDate($closeDate)
    {
        $this->closeDate = $closeDate;

        return $this;
    }

    /**
     * Get closeDate
     *
     * @return datetime_immutable
     */
    public function getCloseDate()
    {
        return $this->closeDate;
    }

    /**
     * Set attachedFiles
     *
     * @param string $document
     *
     * @return Project
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get attachedFiles
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set creator
     *
     * @param string $creator
     *
     * @return Project
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return string
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @return mixed
     */
    public function getDiscussion()
    {
        return $this->discussion;
    }

    /**
     * @param mixed $discussion
     */
    public function setDiscussion($discussion)
    {
        $this->discussion = $discussion;
    }

    /**
     * Set members
     *
     * @param string $members
     *
     * @return Project
     */
    public function setMembers($members)
    {
        $this->members = $members;
        $this->addCreatorInMembersIfNotSelected();
        $this->discussion->setMembers($members);
        return $this;
    }

    /**
     * Get members
     *
     * @return string
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Add member
     *
     * @param \AppBundle\Entity\Owner $member
     *
     * @return Project
     */
    public function addMember(\AppBundle\Entity\Owner $member)
    {
        $this->members[] = $member;

        return $this;
    }

    /**
     * Add document
     *
     * @param string $document
     *
     * @return Project
     */
    public function addDocument($file)
    {
        $this->document[] = $file;

        return $this;
    }


    private function addCreatorInMembersIfNotSelected()
    {
        //if user selected do not add in members
        $isSelected = false;
        foreach ($this->members as $member){
            if($member->getId() == $this->creator->getId()) {
                $isSelected = true;break;
            }
        }
        if(!$isSelected){
            $this->addMember($this->creator);
        }
    }
}

