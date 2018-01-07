<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PostRemove;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="charge")
 * @ORM\HasLifecycleCallbacks
 */
class Charge
{
    /**
     * @return mixed
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param mixed $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }
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
     * @ORM\Column(type="float")
     */
    private $cost;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dueDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $document;

    /**
     * @ORM\JoinColumn(name="id_owners", referencedColumnName="id", nullable=true)
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Owner", mappedBy="charges")
     */
    private $concernedOwners;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contract")
     */
    private $contract;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Payment", mappedBy="charge")
     */
    private $payment;

    /**
     * @return mixed
     */
    public function getConcernedOwners()
    {
        return $this->concernedOwners;
    }

    /**
     * @param mixed $concernedOwners
     */
    public function setConcernedOwners($concernedOwners  = null)
    {
        $this->concernedOwners = $concernedOwners;
    }

    /**
     * @return mixed
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param mixed $contract
     */
    public function setContract($contract)
    {
        $this->contract = $contract;
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
     * @return Charge
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
     * Set cost
     *
     * @param float $cost
     *
     * @return Charge
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return Charge
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Charge
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set document
     *
     * @param string $document
     *
     * @return Charge
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->concernedOwners = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = true;
    }

    /**
     * Add concernedOwner
     *
     * @param \AppBundle\Entity\Owner $concernedOwner
     *
     * @return Charge
     */
    public function addConcernedOwner(\AppBundle\Entity\Owner $concernedOwner)
    {
        $this->concernedOwners[] = $concernedOwner;

        return $this;
    }

    /**
     * Remove concernedOwner
     *
     * @param \AppBundle\Entity\Owner $concernedOwner
     */
    public function removeConcernedOwner(\AppBundle\Entity\Owner $concernedOwner)
    {
        $this->concernedOwners->removeElement($concernedOwner);
    }

    /**
     * @PostRemove()
     */
    public function postRemoveDeleteDoc(){
        try{
            unlink($this->document);
        }catch (ContextErrorException $e){

        }

    }
    public function __toString()
    {
        return $this->subject;
    }
}
