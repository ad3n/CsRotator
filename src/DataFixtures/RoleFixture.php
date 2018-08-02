<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $role1 = new Role();
        $role1->setGroup($this->getReference('group'));
        $role1->setMenu($this->getReference('menu1'));
        $role1->setAddable(true);
        $role1->setEditable(true);
        $role1->setViewable(true);
        $role1->setDeletable(true);
        $manager->persist($role1);

        $role2 = new Role();
        $role2->setGroup($this->getReference('group'));
        $role2->setMenu($this->getReference('menu2'));
        $role2->setAddable(true);
        $role2->setEditable(true);
        $role2->setViewable(true);
        $role2->setDeletable(true);
        $manager->persist($role2);

        $role3 = new Role();
        $role3->setGroup($this->getReference('group'));
        $role3->setMenu($this->getReference('menu3'));
        $role3->setAddable(true);
        $role3->setEditable(true);
        $role3->setViewable(true);
        $role3->setDeletable(true);
        $manager->persist($role3);

        $role4 = new Role();
        $role4->setGroup($this->getReference('group'));
        $role4->setMenu($this->getReference('menu4'));
        $role4->setAddable(true);
        $role4->setEditable(true);
        $role4->setViewable(true);
        $role4->setDeletable(true);
        $manager->persist($role4);

        $role5 = new Role();
        $role5->setGroup($this->getReference('group'));
        $role5->setMenu($this->getReference('menu5'));
        $role5->setAddable(true);
        $role5->setEditable(true);
        $role5->setViewable(true);
        $role5->setDeletable(true);
        $manager->persist($role5);

        $role6 = new Role();
        $role6->setGroup($this->getReference('group'));
        $role6->setMenu($this->getReference('menu6'));
        $role6->setAddable(true);
        $role6->setEditable(true);
        $role6->setViewable(true);
        $role6->setDeletable(true);
        $manager->persist($role6);

        $role7 = new Role();
        $role7->setGroup($this->getReference('group'));
        $role7->setMenu($this->getReference('menu7'));
        $role7->setAddable(true);
        $role7->setEditable(true);
        $role7->setViewable(true);
        $role7->setDeletable(true);
        $manager->persist($role7);

        $role8 = new Role();
        $role8->setGroup($this->getReference('group'));
        $role8->setMenu($this->getReference('menu8'));
        $role8->setAddable(true);
        $role8->setEditable(true);
        $role8->setViewable(true);
        $role8->setDeletable(true);
        $manager->persist($role8);

        $manager->flush();
    }
}
