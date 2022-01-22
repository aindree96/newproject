<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Factory\UserFactory;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
       $user=new User();
       $user->setEmail('admin@gmail.com');
       $user->setRoles(['ROLE_ADMIN']);

       $password = $this->hasher->hashPassword($user, 'admina');
       $user->setPassword($password);
       $manager->persist($user);
       $manager->flush();
    }
}
