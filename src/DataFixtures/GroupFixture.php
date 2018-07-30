<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GroupFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $group = new Group();
        $group->setName('Super Administrator');

        $this->addReference('group', $group);

        $manager->persist($group);
        $manager->flush();
    }
}
