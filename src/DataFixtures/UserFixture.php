<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setGroup($this->getReference('group'));
        $user->setClient($this->getReference('client'));
        $user->setUsername('admin');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'admin'));

        $manager->persist($user);

        $manager->flush();
    }
}
