<?php

namespace App\DataFixtures;

use App\Entity\Product;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ProductFixture extends Fixture
{
    /**
     * Constructor
     */
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir
    ) {}

    /**
     * Load data fixtures with the passed ObjectManager
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $pictures = $this->getPictures();

        for ($i = 1; $i <= 20; $i++) {
            $product = new Product();

            $product->setName($faker->words(3, true));
            $product->setPicture('/images/products/' . $pictures[array_rand($pictures)]);
            $product->setExcerpt('This is a sample product excerpt.');
            $product->setDescription($faker->paragraph());
            $product->setAvailable($faker->boolean());
            $product->setPrice($faker->randomNumber(4, true));
            $product->setCreatedAt(new DateTimeImmutable());

            $manager->persist($product);
        }

        $manager->flush();
    }

    /**
     * Get pictures from the fixtures directory
     *
     * @return array
     */
    private function getPictures(): array
    {
        $picturesDir = $this->projectDir . '/public/images/products';

        return array_filter(scandir($picturesDir), function ($file) {
            return !in_array($file, ['.', '..']) && preg_match('/\.(webp)$/i', $file);
        });
    }
}
