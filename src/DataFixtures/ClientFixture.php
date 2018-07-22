<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ClientFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client->setName('Clorismen');

        $this->addReference('client', $client);

        $manager->persist($client);
        $manager->flush();
    }
}
