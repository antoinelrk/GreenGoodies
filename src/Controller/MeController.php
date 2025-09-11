<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MeController extends AbstractController
{
    #[Route('/my-profile', name: 'app_me')]
    public function __invoke(): Response
    {
        return $this->render('pages/my-profile.html.twig');
    }
}
