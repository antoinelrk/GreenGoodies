<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<CartItem>
 */
class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        protected readonly EntityManagerInterface $entityManager,
        protected readonly CartRepository $cartRepository
    )
    {
        parent::__construct($registry, CartItem::class);
    }

    /**
     * Add an item to the cart.
     *
     * @param Cart $cart
     * @param Product $product
     * @param Request $request
     *
     * @return CartItem
     * @throws ORMException
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

        $this->entityManager->refresh($cart);

        $this->cartRepository->updateTotalPrice($cart);

        return $item;
    }

    /**
     * Update an item in the cart.
     *
     * @param CartItem $item
     * @param int $quantity
     *
     * @return CartItem
     * @throws ORMException
     */
    public function update(CartItem $item, int $quantity): CartItem
    {
        $item->setQuantity($quantity);
        $item->setSubTotal($item->getProduct()->getPrice() * $quantity);
        $this->entityManager->persist($item);
        $this->entityManager->flush();

        $cart = $item->getCart();
        $this->entityManager->refresh($cart);

        $this->cartRepository->updateTotalPrice($cart);

        return $item;
    }

    /**
     * Remove an item from the cart.
     *
     * @param CartItem $item
     *
     * @return bool|null
     *
     * @throws Exception
     */
    public function remove(CartItem $item): bool
    {
        $cart = $item->getCart();

        try {
            $this->entityManager->wrapInTransaction(function () use ($cart, $item) {
                // Remove the item from the cart
                $this->entityManager->remove($item);
                $this->entityManager->flush();

                // Refresh the cart to ensure it's up-to-date
                $this->cartRepository->updateTotalPrice($cart);

                $this->entityManager->getConnection()->commit();
            });

            return true;
        } catch (\Exception $e) {}

        return false;
    }
}
