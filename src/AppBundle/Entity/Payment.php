<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 * @ORM\Entity
 * @ORM\Table(name="payment")
 * @ORM\HasLifecycleCallbacks
 */
class Payment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param mixed $document
     */
    public function setDocument($document)
    {
        $this->document = $document;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getCharge()
    {
        return $this->charge;
    }

    /**
     * @param mixed $charge
     */
    public function setCharge($charge)
    {
        $this->charge = $charge;
    }

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;
    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $document;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Charge", inversedBy="payment")
     */
    private $charge;

    /**
     * @ORM\Column(type="boolean")
     */
    private $done;

    /**
     * @return mixed
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * @param mixed $done
     */
    public function setDone($done)
    {
        $this->done = $done;
    }

    /**
     * Payment constructor.
     */
    public function __construct()
    {
        //$this->contract = new ArrayCollection();
        $this->charge = new ArrayCollection();
        $this->required = true ;
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contract", inversedBy="payment")
     */
    private $contract;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Owner", inversedBy="payment")
     */
    private $owner;

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
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
     * Set amount
     *
     * @param float $amount
     *
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    public function __toString()
    {
        return strval($this->amount) . " €";
    }

    /**
     *
     */
    public function checkAmount()
    {
        $payments = $this->getCharge()->getPayment();
        $paid = 0;
        if ($payments) {
            foreach ($payments as $payment) {
                $paid += $payment->getAmount();
            }
            if ($paid + $this->getAmount() > $this->getCharge()->getCost()
                or $this->getAmount() > $this->getCharge()->getCost() / (sizeof($this->getCharge()->getConcernedOwners()) +1)
            ) {
                throw new \Exception('Payment amount superior to task cost');
            } else if ($paid + $this->getAmount() == $this->getCharge()->getCost()) {
                $this->getCharge()->setStatus(True);
            }
        }
    }
}

