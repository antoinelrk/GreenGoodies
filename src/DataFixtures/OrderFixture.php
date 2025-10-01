<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OrderFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // TODO: Récupérer JohnDoe pour commencer

        $order = new Order();

        // TODO: Faire une boucle sur les utilisateurs pour créer des commandes aléatoires (0 - 5 commandes).
    }
}
