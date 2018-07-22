<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ContactFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $contact1 = new Contact();
        $contact1->setName('Meta');
        $contact1->setClient($this->getReference('client'));
        $contact1->setWhatsAppNumber('6285887538137');

        $this->addReference('contact1', $contact1);
        $manager->persist($contact1);

        $contact2 = new Contact();
        $contact2->setName('Rustan');
        $contact2->setClient($this->getReference('client'));
        $contact2->setWhatsAppNumber('6285814028733');

        $this->addReference('contact2', $contact2);
        $manager->persist($contact2);

        $contact3 = new Contact();
        $contact3->setName('Yuning');
        $contact3->setClient($this->getReference('client'));
        $contact3->setWhatsAppNumber('6285781187813');

        $this->addReference('contact3', $contact3);
        $manager->persist($contact3);

        $contact4 = new Contact();
        $contact4->setName('Zulham');
        $contact4->setClient($this->getReference('client'));
        $contact4->setWhatsAppNumber('6285773109544');

        $this->addReference('contact4', $contact4);
        $manager->persist($contact4);

        $contact5 = new Contact();
        $contact5->setName('Umar');
        $contact5->setClient($this->getReference('client'));
        $contact5->setWhatsAppNumber('6287874986036');

        $this->addReference('contact5', $contact5);
        $manager->persist($contact5);

        $contact6 = new Contact();
        $contact6->setName('Deden');
        $contact6->setClient($this->getReference('client'));
        $contact6->setWhatsAppNumber('6285887223067');

        $this->addReference('contact6', $contact6);
        $manager->persist($contact6);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClientFixture::class,
        ];
    }
}
