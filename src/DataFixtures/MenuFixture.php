<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Menu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $menu1 = new Menu();
        $menu1->setCode('ADMIN');
        $menu1->setName('Administrator');
        $menu1->setIconClass('shield');
        $menu1->setMenuOrder(1);

        $manager->persist($menu1);
        $this->addReference('menu1', $menu1);

        $menu2 = new Menu();
        $menu2->setParent($menu1);
        $menu2->setCode('MENU');
        $menu2->setName('Menu');
        $menu2->setMenuOrder(1);
        $menu2->setRouteName('menus_index');

        $manager->persist($menu2);
        $this->addReference('menu2', $menu2);

        $menu3 = new Menu();
        $menu3->setParent($menu1);
        $menu3->setCode('GROUP');
        $menu3->setName('Group');
        $menu3->setMenuOrder(2);
        $menu3->setRouteName('groups_index');

        $manager->persist($menu3);
        $this->addReference('menu3', $menu3);

        $menu4 = new Menu();
        $menu4->setParent($menu1);
        $menu4->setCode('USER');
        $menu4->setName('User');
        $menu4->setMenuOrder(3);
        $menu4->setRouteName('users_index');

        $manager->persist($menu4);
        $this->addReference('menu4', $menu4);

        $menu5 = new Menu();
        $menu5->setCode('MASTER');
        $menu5->setName('Master');
        $menu5->setIconClass('database');
        $menu5->setMenuOrder(2);

        $manager->persist($menu5);
        $this->addReference('menu5', $menu5);

        $menu6 = new Menu();
        $menu6->setParent($menu5);
        $menu6->setCode('KLIEN');
        $menu6->setName('Klien');
        $menu6->setMenuOrder(1);
        $menu6->setRouteName('clients_index');

        $manager->persist($menu6);
        $this->addReference('menu6', $menu6);

        $menu7 = new Menu();
        $menu7->setParent($menu5);
        $menu7->setCode('CAMPAIGN');
        $menu7->setName('Program');
        $menu7->setMenuOrder(2);
        $menu7->setRouteName('campaigns_index');

        $manager->persist($menu7);
        $this->addReference('menu7', $menu7);

        $manager->flush();
    }
}
