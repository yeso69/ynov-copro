<?php
namespace Tests\AppBundle\Repository;

use AppBundle\Entity\Charge;
use AppBundle\Entity\Owner;
use AppBundle\Entity\Payment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @expectedException \Exception
     */
    public function testConstraints()
    {
        $charge = $this->em->getRepository(Charge::class)->find(1);
        $payment = new Payment();
        $payment-> setAmount(10000000);
        $payment->setOwner($this->em->getRepository(Owner::class)->find(1));
        $payment->setCharge($charge);
        $this->em->persist($payment);
        $this->expectException(\Exception::class);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}