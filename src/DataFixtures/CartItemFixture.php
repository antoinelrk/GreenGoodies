<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CartItemFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $carts = $manager->getRepository(Cart::class)->findAll();
        $products = $manager->getRepository(Product::class)->findAll();

        foreach ($carts as $cart) {
            $nbItems = $faker->numberBetween(0, 8);
            $selectedProducts = $faker->randomElements($products, $nbItems);
            $totalPrice = 0;

            foreach ($selectedProducts as $product) {
                $numberOfProducts = $faker->numberBetween(1, 5);
                $subTotal = $product->getPrice() * $numberOfProducts;

                $cartItem = new CartItem();
                $cartItem->setCart($cart);
                $cartItem->setProduct($product);
                $cartItem->setQuantity($numberOfProducts);
                $cartItem->setSubTotal($subTotal);

                $manager->persist($cartItem);

                $totalPrice += $subTotal;
            }

            if ($nbItems > 0) {
                $cart->setTotalPrice($totalPrice);
                $manager->persist($cart);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            ProductFixture::class,
        ];
    }
}
