<?php
/**
 * Created by PhpStorm.
 * User: mbela
 * Date: 12/04/2017
 * Time: 11:42
 */


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Owner;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Tests\Compiler\C;
use Symfony\Component\HttpKernel\KernelInterface;


class DataLoader implements FixtureInterface, ContainerAwareInterface {
    /** @var  ContainerInterface */
    private $container;


    /** @var  EntityManager */
    private $em;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->em=$manager;
        $toto = new Owner();
        $toto->setFirstname("toto");
        $toto->setLastname("titi");
        $this->em->flush();

        //Debug::dump($oneCar);
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
       $this->container = $container;
    }
    /**@var KernelInterface */
    private function getCurrentEnvironnement(){
        $kernel = $this->container->get('kernel');
        return $kernel->getEnvironment();
    }
}
// php bin/console doctrine:fixtures:load --no-interaction