<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\CampaignContact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CampaignContactFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $campaignContact1 = new CampaignContact();
        $campaignContact1->setCampaign($this->getReference('campaign'));
        $campaignContact1->setContact($this->getReference('contact1'));
        $manager->persist($campaignContact1);

        $campaignContact2 = new CampaignContact();
        $campaignContact2->setCampaign($this->getReference('campaign'));
        $campaignContact2->setContact($this->getReference('contact2'));
        $manager->persist($campaignContact2);

        $campaignContact3 = new CampaignContact();
        $campaignContact3->setCampaign($this->getReference('campaign'));
        $campaignContact3->setContact($this->getReference('contact3'));
        $manager->persist($campaignContact3);

        $campaignContact4 = new CampaignContact();
        $campaignContact4->setCampaign($this->getReference('campaign'));
        $campaignContact4->setContact($this->getReference('contact4'));
        $manager->persist($campaignContact4);

        $campaignContact5 = new CampaignContact();
        $campaignContact5->setCampaign($this->getReference('campaign'));
        $campaignContact5->setContact($this->getReference('contact5'));
        $manager->persist($campaignContact5);

        $campaignContact6 = new CampaignContact();
        $campaignContact6->setCampaign($this->getReference('campaign'));
        $campaignContact6->setContact($this->getReference('contact6'));
        $manager->persist($campaignContact6);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClientFixture::class,
            CampaignFixture::class,
        ];
    }
}
