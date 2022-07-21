<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addPermission($manager);
        $this->addRoles($manager);
        $this->addUser($manager);

        $manager->flush();
    }

    public function addRoles(ObjectManager $manager)
    {
        $roleAdmin = new Role();
        $roleAdmin->setName('Admin');
        $roleAdmin->setPermissions(new ArrayCollection(
            [
                $this->getReference('CAN_ADD_IMAGE_POST'),
                $this->getReference('CAN_VIEW_IMAGE_POST'),
                $this->getReference('CAN_DELETE_IMAGE_POST'),
                $this->getReference('CAN_UPDATE_IMAGE_POST'),
            ]
        ));
        $this->addReference('ADMIN', $roleAdmin);
        $manager->persist($roleAdmin);

        $roleClient = new Role();
        $roleClient->setName('Client');
        $roleClient->setPermissions(new ArrayCollection(
            [
                $this->getReference('CAN_VIEW_IMAGE_POST'),
            ]
        ));
        $manager->persist($roleClient);

        $manager->flush();
    }

    public function addPermission(ObjectManager $manager)
    {
        $permission1 = new Permission();
        $permission1->setName('CAN_ADD_IMAGE_POST');
        $this->addReference('CAN_ADD_IMAGE_POST', $permission1);
        $manager->persist($permission1);

        $permission2 = new Permission();
        $permission2->setName('CAN_VIEW_IMAGE_POST');
        $this->addReference('CAN_VIEW_IMAGE_POST', $permission2);
        $manager->persist($permission2);

        $permission3 = new Permission();
        $permission3->setName('CAN_DELETE_IMAGE_POST');
        $this->addReference('CAN_DELETE_IMAGE_POST', $permission3);
        $manager->persist($permission3);

        $permission4 = new Permission();
        $permission4->setName('CAN_UPDATE_IMAGE_POST');
        $this->addReference('CAN_UPDATE_IMAGE_POST', $permission4);
        $manager->persist($permission4);

        $manager->flush();
    }

    public function addUser(ObjectManager $manager)
    {

        $user = new User('admin');
        //password is password
        $user->setPassword('$2y$13$GZi3ZUaa0nRV9yUt4t7zgOlD.Co.g0kukaI/GrhqJDJYV.DHlqBW6');
        $user->setEmail('admin@gmail.com');
        $user->setUsername('admin');
        $user->setRoles(new ArrayCollection(
            [
                $this->getReference('ADMIN'),
            ]
        ));
        $manager->persist($user);
        $manager->flush();
    }

}
