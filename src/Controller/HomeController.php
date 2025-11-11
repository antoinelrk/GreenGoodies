<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {}

    /**
     * Displays the home page with a list of products.
     *
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        $products = $this->productRepository->collect();

        return $this->render('pages/home.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/_dev/mail', name: 'dev_mail')]
    public function __invoke(MailerInterface $mailer): Response
    {
        $mailer->send(
            (new Email())
                ->from('no-reply@green-goodies.co')
                ->to('test@example.test')
                ->subject('Ping Mailpit')
                ->text('OK')
                ->html('<p>✅ Mailpit fonctionne.</p>')
        );

        return new Response('Envoyé. Ouvre http://localhost:1080 (UI Mailpit).');
    }
}
