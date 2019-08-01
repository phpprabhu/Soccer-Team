<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load database with 2 dummy users
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $plainPassword = 'secret';
        $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);

        $user->setPassword($encoded);
        $user->setUsername('admin');
        $user->setRoles(array("ROLE_ADMIN"));
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $plainPassword = 'secret';
        $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);

        $user->setPassword($encoded);
        $user->setUsername('user');
        $user->setRoles(array("ROLE_USER"));
        $manager->persist($user);
        $manager->flush();
    }
}
