<?php

namespace App\DataFixtures;

use App\Entity\Product;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // tu peux mettre 'en_US' ou autre

        for ($i = 1; $i <= 20; $i++) {
            $product = new Product();

            $product->setName($faker->words(3, true));
            $product->setPicture('https://placehold.co/150');
            $product->setExcerpt('This is a sample product excerpt.');
            $product->setDescription($faker->paragraph());
            $product->setAvailable($faker->boolean());
            $product->setPrice($faker->randomNumber(4, true));
            $product->setCreatedAt(new DateTimeImmutable());

            $manager->persist($product);
        }

        $manager->flush();
    }
}
