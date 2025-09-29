<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    public function __construct(
        protected readonly ProductRepository $productRepository,
        protected readonly CartRepository $cartRepository
    ) {}

    /**
     * Displays a single product by its ID.
     *
     * @param mixed $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/products/{id}', name: 'products.show')]
    public function show($id)
    {
        $product = $this->productRepository->find($id);

        $item = false;
        $cart = null;

        if ($this->getUser()) {
            $cart = $this->cartRepository->get($this->getUser()->getId());
            $item = $cart->getCartItems()->findFirst(
                fn($key, $item) => $item->getProduct()->getId() === $product->getId()
            ) ?? false;
        }

        return $this->render(
            'products/show.html.twig',
            compact('product', 'cart', 'item')
        );
    }
}

