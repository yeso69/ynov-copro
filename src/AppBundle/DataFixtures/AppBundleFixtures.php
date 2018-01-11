<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Discussion;
use AppBundle\Entity\Message;
use AppBundle\Entity\Owner;
use AppBundle\Entity\Contract;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppBundleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {


        $contract = new Contract();
        $contract->setProvider('Provider 2');
        $manager->persist($contract);

        $manager->flush();

        $user = new Owner();
        $user->setUsername("admin");
        $user->setFirstname("Hello");
        $user->setLastname("World");
        $user->setPlainPassword("admin");
        $user->setEmail("ok@ok.fr");
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setEnabled(true);

        $manager->persist($user);
        $manager->flush();

        $discus = new Discussion();
        $discus->setCreator($user);
        $discus->setPublic(true);
        $discus->setSubject('Test');

        $manager->persist($discus);
        $manager->flush();

    }
}