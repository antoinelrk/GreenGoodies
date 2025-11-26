<?php

namespace App\Controller\API;

use App\Repository\ProductRepository;
use App\Security\Voter\ApiVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
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
        if (!$this->isGranted(ApiVoter::ACCESS)) {
            return new JsonResponse(
                ['error' => 'access_denied', 'message' => 'Votre accès API est désactivé.'],
                403
            );
        }

        $products = $repository->findAll();

        return $this->json(
            $products,
            200,
            [],
            ['groups' => 'products:list']
        );
    }
}
