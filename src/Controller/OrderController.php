<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    public function __construct(
        protected CartRepository $cartRepository,
        protected OrderRepository $orderRepository,
        protected EntityManagerInterface $entityManager
    ) {}

    #[Route('/order', name: 'app_order_checkout', methods: ['POST'])]
    public function checkout(): Response
    {
        $cart = $this->cartRepository->findOneBy(['customer' => $this->getUser()]);

        if (!$cart) {
            return $this->redirectToRoute('app_cart_index');
        }

        $this->entityManager->wrapInTransaction(function () use ($cart) {
            $this->orderRepository->createOrderFromCart($cart);

            $this->cartRepository->clear($cart);
        });

        return $this->redirectToRoute('app_me');
    }
}
