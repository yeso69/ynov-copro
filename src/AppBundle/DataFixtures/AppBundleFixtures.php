<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Discussion;
use AppBundle\Entity\Message;
use AppBundle\Entity\Owner;
use AppBundle\Entity\Contract;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;

class AppBundleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $toto = new Owner();
        $toto->setUsername('toto');
        $toto->setFirstname("toto");
        $toto->setLastname("titi");
        $toto->setEmail('toto@lol.fr');
        $toto->setPlainPassword("toto");
        $toto->setRoles(['ROLE_USER']);
        $toto->setEnabled(true);

        $manager->persist($toto);
        $manager->flush();

        $tata = new Owner();
        $tata->setUsername('tata');
        $tata->setFirstname("tata");
        $tata->setLastname("tata");
        $tata->setEmail('tata@lol.fr');
        $tata->setPlainPassword("tata");
        $tata->setRoles(['ROLE_USER']);
        $tata->setEnabled(true);

        $manager->persist($tata);
        $manager->flush();


        $admin = new Owner();
        $admin->setUsername('admin');
        $admin->setFirstname("admin");
        $admin->setLastname("admin");
        $admin->setEmail('admin@admin.fr');
        $admin->setPlainPassword("admin");
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setEnabled(true);

        $manager->persist($admin);
        $manager->flush();

        $contract = new Contract();
        $contract->setProvider('Contract travaux toit');
        $manager->persist($contract);

        $manager->flush();

/*        $discus = new Discussion();
        $discus->setCreator($toto);
        $discus->setPublic(true);
        $discus->setSubject('Test');
        $discus->addMember($tata);

        $manager->persist($discus);
        $manager->flush();*/

/*        $msg = new Message();
        $msg->setSubject('Important message');
        $msg->setAuthor($toto);
        $msg->setContent('Hey here is my message');
        $msg->setDiscussion($discus);
        $msg->setArchived(false);

        $manager->persist($msg);
        $manager->flush();*/

    }
}