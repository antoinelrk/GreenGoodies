<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    public function __construct(
        protected readonly ProductRepository $productRepository
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

        return $this->render(
            'products/show.html.twig',
            ['product' => $product]
        );
    }
}

