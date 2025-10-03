<?php

namespace App\Controller;


use App\Repository\OrderRepository;
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
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly OrderRepository $orderRepository,
    ) {}

    #[Route('/me', name: 'app_me')]
    public function me(): Response
    {
        $user = $this->getUser();
        $orders = $this->orderRepository->findByUser($user);

        return $this->render('pages/me.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/account/toggle-api', name: 'app_api_toggle', methods: ['POST'])]
    public function toggleApi(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Aucun utilisateur connecté.');
            return $this->redirectToRoute('app_login');
        }

        $user->setApiEnabled(!$user->getApiEnabled());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->addFlash('success', 'Le mode API a été '.($user->getApiEnabled() ? 'activé' : 'désactivé').'.');

        return $this->redirectToRoute('app_me');
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
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack,
    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Aucun utilisateur connecté.');
            return $this->redirectToRoute('app_login');
        }

        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();

        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
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
