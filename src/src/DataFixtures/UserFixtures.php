<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

   private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User(); 
        $admin
            ->setName('admin')
            ->setSurname('admin')
            ->setEmail('admin@email.com')
            ->setRoles('ROLE_ADMIN')
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'admin')); 

        $manager->persist($admin);

        $user = new User();
        $user
            ->setName('user')
            ->setSurname('user')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'user'))
            ->setEmail('user@email.com')
            ->setRoles('ROLE_USER'); 

        $manager->persist($user);
        $manager->flush();
    }
}
