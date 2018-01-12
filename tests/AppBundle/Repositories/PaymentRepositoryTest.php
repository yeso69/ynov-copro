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
    public function testConstraint()
    {
        $charge = $this->em->getRepository(Charge::class)->find(4);
        $payment = new Payment();
        $payment-> setAmount(10000000);
        $payment->setOwner($this->em->getRepository(Owner::class)->find(1));
        $payment->setCharge($charge);
        $this->em->persist($payment);
        $this->expectException(\Exception::class);
    }

    public function testUpdate()
    {
        $charge = new Charge();
        $charge->setSubject("Test");
        $charge->setCost(1000);
        $charge->setStatus(false);
        $charge->setDueDate(new\DateTime('10-10-2018'));


        $this->em->persist($charge);
        $this->em->flush();

        $payment = new Payment();
        $payment-> setAmount(1000);
        $payment->setOwner($this->em->getRepository(Owner::class)->find(1));
        $payment->setCharge($charge);
        $this->em->persist($payment);

        $this->assertEquals(true, $charge->getStatus());
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