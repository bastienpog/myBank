<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Operation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("test@mybank.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->hasher->hashPassword($user, "password"));
        $manager->persist($user);

        $category = new Category();
        $category->setTitle("Housing");
        $category->setUser($user);
        $manager->persist($category);

        $user2 = new User();
        $user2->setEmail("test2@mybank.com");
        $user2->setRoles(["ROLE_USER"]);
        $user2->setPassword($this->hasher->hashPassword($user2, "password"));
        $manager->persist($user2);

        $category2 = new Category();
        $category2->setTitle("Food");
        $category2->setUser($user2);
        $manager->persist($category2);

        $operation = new Operation();
        $operation->setLabel("Rent");
        $operation->setAmount("800.00");
        $operation->setDate(new \DateTime("2026-01-01"));
        $operation->setCategory($category);
        $operation->setUser($user);
        $manager->persist($operation);

        $manager->flush();

        $this->addReference("user", $user);
        $this->addReference("category", $category);
        $this->addReference("user2", $user2);
        $this->addReference("category2", $category2);
    }
}
