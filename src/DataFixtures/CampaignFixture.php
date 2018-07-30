<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Campaign;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CampaignFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $campaign1 = new Campaign();
        $campaign1->setClient($this->getReference('client'));
        $campaign1->setName('Beli Sabun Cloris');
        $campaign1->setFacebookPixel('199316867302387');
        $campaign1->setGreetingMessage('Halo gan, mau tanya produk cloris soapnya');

        $this->addReference('campaign1', $campaign1);
        $manager->persist($campaign1);

        $campaign2 = new Campaign();
        $campaign2->setClient($this->getReference('client'));
        $campaign2->setName('Beli Sabun Cloris Form');
        $campaign2->setFacebookPixel('199316867302387');
        $campaign2->setGreetingMessage('Halo gan, mau tanya produk cloris soapnya');

        $this->addReference('campaign2', $campaign2);
        $manager->persist($campaign2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClientFixture::class,
        ];
    }
}
