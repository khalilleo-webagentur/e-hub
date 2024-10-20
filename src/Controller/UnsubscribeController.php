<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\SubscriberService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UnsubscribeController extends AbstractController
{
    use FormValidationTrait;

    private const HOME_ROUTE = 'app_home';

    public function __construct(
        private readonly SubscriberService $newsletterService,
    ) {
    }

    #[Route('/un-subscribe/{token}', name: 'app_home_newsletter_unsubscribe', methods: ['GET', 'POST'])]
    public function store(string $token): Response
    {
        $token = $this->validate($token);

        $subscriber = $this->newsletterService->getByToken($token);

        if (!$subscriber) {
            $this->addFlash('warning', 'Token is invalid.');
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        $this->newsletterService->unSubscribeUser($subscriber);

        $this->addFlash('success', 'Your email has been removed from our subscription List.');

        return $this->redirectToRoute(self::HOME_ROUTE);
    }
}
