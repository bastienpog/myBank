<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
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

        $manager->flush();

        // Rend les objets accessibles dans d'autres fixtures si besoin
        $this->addReference("user", $user);
        $this->addReference("category", $category);
    }
}
