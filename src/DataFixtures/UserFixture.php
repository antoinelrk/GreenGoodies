<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enums\RolesEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Create an admin user
        $user = new User();
        $user->setEmail('john@doe.fr');
        $user->setRoles([RolesEnum::ROLE_ADMIN->value]);
        $user->setPassword(password_hash('P@ss1234', PASSWORD_ARGON2ID));
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setApiEnabled(true);
        $user->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($user);

        // Create random users
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();

            $user->setEmail($faker->email());
            $user->setRoles([RolesEnum::weightedRandom([
                RolesEnum::ROLE_USER->value => 90,
                RolesEnum::ROLE_ADMIN->value => 10,
            ])]);
            $user->setPassword(password_hash('P@ss1234', PASSWORD_ARGON2ID));
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setApiEnabled($faker->boolean());
            $user->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($user);
        }

        $manager->flush();
    }
}
