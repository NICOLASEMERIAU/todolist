<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        //création des users
        for ($i = 1; $i < 3; $i++) {
            $user = new User();
            $user->setUsername($i);
            $user->setEmail('user'.$i.'@todolist.com');
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
            $user->setRoles(["ROLE_USER"]);
            $manager->persist($user);
            $users[] = $user;
        }

        //création d'un admin
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@todolist.com');
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, "password"));
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);


        //création des taches
        for ($k = 0; $k < 20; $k++) {
            $product = new Task();
            $product->setTitle('title'.$k);
            $product->setContent('contenu'.$k);
            $product->setCreatedAt(new \DateTimeImmutable('now + '.$k.'seconds'));
            $product->setUser($users[array_rand($users)]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
