<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CartController extends AbstractController
{
    /**
     * Constructor.
     */
    public function __construct(
        protected readonly CartRepository $cartRepository,
        protected readonly CartItemRepository $cartItemRepository,
    ) {}

    /**
     * Display the cart.
     *
     * @return Response
     */
    #[Route('cart', name: 'app_cart')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(): Response
    {
        $cart = $this->cartRepository->get($this->getUser()->getId());

        return $this->render('cart/index.html.twig', compact('cart'));
    }

    /**
     * Add a product to the cart.
     *
     * @param Product $product
     * @param Request $request
     *
     * @return Response
     */
    #[Route('cart/add/{product}', name: 'app_cart_add', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function add(Product $product, Request $request): Response
    {
        $cart = $this->cartRepository->get($this->getUser()->getId());

        $this->cartItemRepository->addItemToCart($cart, $product, $request);
        $this->cartRepository->updateTotalPrice($cart);

        return $this->redirectToRoute('app_cart');
    }

    /**
     * Update the quantity of a product in the cart.
     *
     * @param Product $product
     * @param Request $request
     *
     * @return void
     */
    #[Route('cart/update/{product}', name: 'app_cart_update', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function update(Product $product, Request $request)
    {
        // Not implemented
    }
}
