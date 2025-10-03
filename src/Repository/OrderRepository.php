<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        protected EntityManagerInterface $entityManager
    )
    {
        parent::__construct($registry, Order::class);
    }

    public function findByUser($user): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.customer = :customer')
            ->setParameter('customer', $user)
            ->orderBy('o.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function createOrderFromCart(Cart $cart): Order
    {
        $order = new Order();
        $order->setCustomer($cart->getCustomer());
        $order->setTotalAmount($cart->getTotalPrice());
        $order->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }
}
