<?php

namespace App\Controller;


use App\Repository\CartRepository;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class MeController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly OrderRepository $orderRepository,
        private readonly CartRepository $cartRepository,
    ) {}

    #[Route('/me', name: 'app_me')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function me(): Response
    {
        $user = $this->getUser();
        $orders = $this->orderRepository->findByUser($user);

        return $this->render('pages/me.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/account/toggle-api', name: 'app_api_toggle', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
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
     * @return Response
     * @throws Exception
     */
    #[Route('/account/delete', name: 'app_remove_account', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(TokenStorageInterface $tokenStorage, RequestStack $requestStack): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Aucun utilisateur connecté.');
            return $this->redirectToRoute('app_login');
        }

        $this->entityManager->wrapInTransaction(function () use (
            $user,
            $tokenStorage,
            $requestStack,
        ) {
            // Log out the user
            $tokenStorage->setToken(null);
            $session = $requestStack->getSession();
            $session->invalidate();

            // Remove user's cart
            $this->cartRepository->remove($user->getCart());

            /**
             * TODO: Déplacer la suppression de l'utilisateur dans son repo.
             */
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre compte a bien été supprimé.');

            return $this->redirectToRoute('app_home');
        });

        return $this->redirectToRoute('app_home');
    }
}
