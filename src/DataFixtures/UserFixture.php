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
        // create 5 products! Bam!
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail(sprintf('spacebar%d@example.com',$i));
            $user->setFullname(sprintf('fullname ',$i));
            $user->setRoles([]);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'engage'
            ));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
