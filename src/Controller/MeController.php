<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MeController extends AbstractController
{
    #[Route('/me', name: 'app_me')]
    public function me(): Response
    {
        return $this->render('pages/me.html.twig');
    }

    public function delete(): Response
    {
        $this->addFlash('success', 'Your account has been deleted.');
        return $this->redirectToRoute('app_home');
    }
}
