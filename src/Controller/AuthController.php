<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    /**
     * @throws \LogicException
     */
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(): Response
    {
        throw new \LogicException('Handled by json_login & Lexik success_handler.');
    }
}
