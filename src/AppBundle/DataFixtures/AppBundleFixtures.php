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

        $toto = new Owner();
        $toto->setFirstname("toto");
        $toto->setLastname("titi");
        $manager->persist($toto);
        $manager->flush();

        $contract = new Contract();
        $contract->setProvider('Provider 2');
        $manager->persist($contract);

        $manager->flush();

        $user = new User();
        $user->setUsername("admin");
        $user->setPlainPassword("admin");
        $user->setEmail("ok@ok.fr");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEnabled(true);

        $manager->persist($user);

        $user = new User();
        $user->setUsername("user");
        $user->setPlainPassword("user");
        $user->setEmail("ok2@ok.fr");
        $user->setRoles(['ROLE_USER']);
        $user->setEnabled(true);

        $manager->persist($user);

        $manager->flush();

        $discus = new Discussion();
        $discus->setCreator($toto);
        $discus->setPublic(true);
        $discus->setSubject('Test');

        $manager->persist($discus);
        $manager->flush();

        $msg = new Message();
        $msg->setSubject('test 1');
        $msg->setAuthor($toto);
        $msg->setContent('POULOULOU');
        $msg->setDiscussion($discus);
        $msg->setArchived(false);

        $manager->persist($msg);
        $manager->flush();
    }
}