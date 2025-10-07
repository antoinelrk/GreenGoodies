<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'api_products_index', methods: ['GET'])]
    #[IsGranted("API_ACCESS")]
    public function index(): \Symfony\Component\HttpFoundation\JsonResponse
    {
        return $this->json([
            'message' => 'Api products route',
        ]);
    }
}
