<?php

namespace App\Controller\API;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProductController extends AbstractController
{
    /**
     * List products
     *
     * @param ProductRepository $repository
     *
     * @return JsonResponse
     */
    #[Route('/api/products', name: 'api_products_index', methods: ['GET'])]
    #[IsGranted("API_ACCESS")]
    public function index(ProductRepository $repository): JsonResponse
    {
        $products = $repository->findAll();

        return $this->json(
            $products,
            200,
            [],
            ['groups' => 'products:list']
        );
    }
}
