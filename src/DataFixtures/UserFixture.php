<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // crear 5 usuarios
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail(sprintf('spacebar%d@example.com',$i));
            $user->setFullname('fullname');
            $user->setRoles([]);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'engage'
            ));
            $user->setSlug('fullname-'.uniqid());
            $manager->persist($user);
        }

        $manager->flush();
    }
}
