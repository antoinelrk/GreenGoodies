<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\User;
use App\Repository\CartItemRepository;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OrderFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            $nbOrders = $faker->numberBetween(0, 5);

            for ($i = 0; $i < $nbOrders; $i++) {
                $totalAmount = $faker->numberBetween(2000, 50000);
                $createdAt = $faker->dateTimeBetween('-2 year', 'now', 'Europe/Paris');
                $createdAt = DateTimeImmutable::createFromMutable($createdAt);

                $order = $this->createOrder(
                    totalAmount: $totalAmount,
                    user: $user,
                    createdAt: $createdAt
                );

                $manager->persist($order);
            }

            $manager->flush();
        }
    }

    /**
     * Create an order
     *
     * @param int $totalAmount
     * @param User $user
     * @param DateTimeImmutable $createdAt
     *
     * @return Order
     */
    private function createOrder(int $totalAmount, User $user, DateTimeImmutable $createdAt): Order
    {
        $order = new Order();
        $order->setTotalAmount($totalAmount);
        $order->setCustomer($user);
        $order->setCreatedAt($createdAt);

        return $order;
    }

    /**
     * This method must return an array of the classes of the fixtures
     * on which the implementing class depends on.
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            ProductFixture::class,
            CartItemRepository::class,
        ];
    }
}
