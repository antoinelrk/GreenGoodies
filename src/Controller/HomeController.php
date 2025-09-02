<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        $products = [
            [
                'name' => 'Product 1',
                'price' => 19.99,
                'description' => 'Description for product 1',
                'image' => 'https://placehold.co/150'
            ],
            [
                'name' => 'Product 2',
                'price' => 29.99,
                'description' => 'Description for product 2',
                'image' => 'https://placehold.co/150'
            ],
            [
                'name' => 'Product 3',
                'price' => 39.99,
                'description' => 'Description for product 3',
                'image' => 'https://placehold.co/150'
            ],
            [
                'name' => 'Product 4',
                'price' => 49.99,
                'description' => 'Description for product 4',
                'image' => 'https://placehold.co/150'
            ],
            [
                'name' => 'Product 5',
                'price' => 59.99,
                'description' => 'Description for product 5',
                'image' => 'https://placehold.co/150'
            ],
            [
                'name' => 'Product 6',
                'price' => 69.99,
                'description' => 'Description for product 6',
                'image' => 'https://placehold.co/150'
            ],
            [
                'name' => 'Product 7',
                'price' => 79.99,
                'description' => 'Description for product 7',
                'image' => 'https://placehold.co/150'
            ],
            [
                'name' => 'Product 8',
                'price' => 89.99,
                'description' => 'Description for product 8',
                'image' => 'https://placehold.co/150'
            ],
            [
                'name' => 'Product 9',
                'price' => 99.99,
                'description' => 'Description for product 9',
                'image' => 'https://placehold.co/150'
            ]
        ];

        return $this->render('pages/home.html.twig', [
            'products' => $products
        ]);
    }
}
