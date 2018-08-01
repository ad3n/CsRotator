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

        $manager->flush();
    }
}
