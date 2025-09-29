<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        protected readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct($registry, Cart::class);
    }

    /**
     * @param int $id User ID
     * @return Cart
     */
    public function get(int $id): Cart
    {
        // TODO: Optimiser cette requête, supprimer les attributs en trop (le password par exemple...)
        return $this->createQueryBuilder('c')
            ->leftJoin('c.cartItems', 'ci')->addSelect('ci')
            ->leftJoin('ci.product', 'p')->addSelect('p')
            ->andWhere('IDENTITY(c.customer) = :uid')   // ⚠️ on compare l'ID de l'association
            ->setParameter('uid', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function updateTotalPrice(Cart $cart): void
    {
        $totalPrice = array_reduce($cart->getCartItems()->toArray(), fn($sum, $item) => $sum + $item->getSubTotal(), 0);
        $cart->setTotalPrice($totalPrice);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }
}
