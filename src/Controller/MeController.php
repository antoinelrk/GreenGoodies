<?php

namespace App\Controller;


use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class MeController extends AbstractController
{
    #[Route('/me', name: 'app_me')]
    public function me(): Response
    {
        return $this->render('pages/me.html.twig');
    }

    /**
     * Delete user account.
     *
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $requestStack
     * @return Response
     * @throws Exception
     */
    #[Route('/account/delete', name: 'app_remove_account', methods: ['POST'])]
    public function delete(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack,
    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Aucun utilisateur connecté.');
            return $this->redirectToRoute('app_login');
        }

        $conn = $entityManager->getConnection();
        $conn->beginTransaction();

        try {
            $entityManager->remove($user);
            $entityManager->flush();
            $conn->commit();
        } catch (\Throwable $e) {
            $conn->rollBack();
            $this->addFlash('error', 'La suppression du compte a échoué. ('.$e->getMessage().')');
            return $this->redirectToRoute('app_home');
        }

        $tokenStorage->setToken(null);
        $session = $requestStack->getSession();
        $session->invalidate();

        $this->addFlash('success', 'Votre compte a bien été supprimé.');
        return $this->redirectToRoute('app_home');
    }
}
