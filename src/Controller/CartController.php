<?php

namespace App\Controller;

use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CartController extends AbstractController
{
    #[Route('cart', name: 'app_cart')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(CartRepository $cartRepository): Response
    {
        $cart = $cartRepository->get($this->getUser()->getId());

        return $this->render('cart/index.html.twig', compact('cart'));
    }
}
