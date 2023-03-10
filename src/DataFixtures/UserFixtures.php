<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setFirstName("Sandra");
        $admin->setLastName("Fernandez");
        $admin->setEmail("admin@pepsdesign.fr");
        $admin->setRoles(["ROLE_ADMIN"]);
        $hashPassword = $this->passwordHasher->hashPassword($admin, "gtt8Lor85#ypza64fs*");
        $admin->setPassword($hashPassword);

        $manager->persist($admin);

        $user = new User();
        $user->setFirstName("John");
        $user->setLastName("Doe");
        $user->setEmail("j.doe@gmail.com");
        $user->setRoles(["ROLE_USER"]);
        $hashPassword = $this->passwordHasher->hashPassword($user, "marseille9P*");
        $user->setPassword($hashPassword);

        $manager->persist($user);

        $manager->flush();
    }
}
