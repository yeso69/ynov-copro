<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Owner;
use AppBundle\Entity\Contract;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppBundleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $toto = new Owner();
        $toto->setFirstname("toto");
        $toto->setLastname("titi");
        $manager->persist($toto);
        $manager->flush();

        $contract = new Contract();
        $contract->setProvider('Provider 2');
        $manager->persist($contract);

        $manager->flush();
    }
}