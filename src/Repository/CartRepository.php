<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function get(int $id): Cart
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.cartItems', 'ci')->addSelect('ci')
            ->leftJoin('ci.product', 'p')->addSelect('p')
            ->andWhere('IDENTITY(c.customer) = :uid')   // ⚠️ on compare l'ID de l'association
            ->setParameter('uid', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
