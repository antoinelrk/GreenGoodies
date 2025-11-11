<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\ORMException;
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
     * @throws ORMException
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
     * @return Response
     *
     * @throws Exception
     * @throws ORMException
     */
    #[Route('cart/update/{product}', name: 'app_cart_update', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function update(Product $product, Request $request): Response
    {
        $cartItem = $this->cartItemRepository->findOneBy([
            'cart' => $this->cartRepository->get($this->getUser()->getId()),
            'product' => $product,
        ]);

        $quantity = (int) $request->get('quantity');

        if ($quantity > 0) {
            $this->cartItemRepository->update(
                $cartItem,
                $quantity
            );
        } else {
            $this->cartItemRepository->remove($cartItem);
        }

        return $this->redirectToRoute('app_cart');
    }

    /**
     * Clear the cart.
     *
     * @return Response
     */
    #[Route('cart/clear', name: 'app_cart_clear', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function clear(): Response
    {
        $cart = $this->cartRepository->get($this->getUser()->getId());
        $this->cartRepository->clear($cart);

        return $this->redirectToRoute('app_cart');
    }
}
