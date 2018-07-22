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
        $campaign = new Campaign();
        $campaign->setClient($this->getReference('client'));
        $campaign->setName('Beli Sabun Cloris');
        $campaign->setFacebookPixel('199316867302387');
        $campaign->setGreetingMessage('Halo gan, mau tanya produk cloris soapnya');

        $this->addReference('campaign', $campaign);

        $manager->persist($campaign);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClientFixture::class,
        ];
    }
}
