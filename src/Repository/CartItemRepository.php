<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<CartItem>
 */
class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        protected readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct($registry, CartItem::class);
    }

    /**
     * Add an item to the cart.
     *
     * @param Cart    $cart
     * @param Product $product
     * @param Request $request
     *
     * @return CartItem
     */
    public function addItemToCart(Cart $cart, Product $product, Request $request): CartItem
    {
        $quantity = $request->request->get('quantity', 1);
        $subTotal = $product->getPrice() * $quantity;

        $item = new CartItem();
        $item->setCart($cart);
        $item->setProduct($product);
        $item->setQuantity($quantity);
        $item->setSubTotal($subTotal);
        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return $item;
    }
}
