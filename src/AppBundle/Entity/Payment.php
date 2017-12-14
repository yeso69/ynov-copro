<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * Payment constructor.
     */
    public function __construct()
    {
        $this->contract = new ArrayCollection();
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
}

