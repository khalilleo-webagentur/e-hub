<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractBaseController;
use App\Mails\Api\SendNewsletterMail;
use App\Service\Core\MonologService;
use App\Service\NewsletterService;
use App\Service\NewsletterSubscriberService;
use App\Service\SubscriberService;
use App\Traits\FormValidationTrait;
use App\Traits\RandomTokenGeneratorTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendNeswletterController extends AbstractBaseController
{
    use FormValidationTrait;
    use RandomTokenGeneratorTrait;

    public function __construct(
        private readonly NewsletterService $newsletterService,
        private readonly SubscriberService $subscriberService,
        private readonly NewsletterSubscriberService $newsletterSubscriberService,
        private readonly MonologService $monolog,
    ) {
    }

    #[Route('/send/newsletter/{token}', name: 'app_api_send_newsletter')]
    public function send(string $token, SendNewsletterMail $sendNewsletterMail): Response|RedirectResponse
    {
        $token = $this->validate($token);

        $newsletter = $this->newsletterService->getOneByTokenAndCanBePublished($token, true);

        if (!$newsletter) {
            $this->monolog->logger->notice(
                sprintf('Newsletter with token [%s] could not be found.', $token)
            );
            return $this->redirectToRoute('app_home');
        }

        $subscriber = $this->subscriberService->getOneTheReceiveNewsletter();

        if (!$subscriber) {
            $this->monolog->logger->notice('No active subscribers found.');
            return $this->redirectToRoute('app_home');
        }

        if ($newsletter->isCanBePublished() && $newsletter->isSent() === false) {

            $sendNewsletterMail->send(
                $subscriber->getName(),
                $subscriber->getEmail(),
                $subscriber->getToken(),
                $newsletter->getTitle(),
                $newsletter->getContent(),
            );

            $this->subscriberService->updateHasReceived($subscriber, true);

            $this->newsletterSubscriberService->create($newsletter, $subscriber);

            $this->monolog->logger->notice(
                sprintf('Newsletter has been sent to %s', $subscriber->getName())
            );

            return new Response('Content has been sent.');
        }

        if ($this->newsletterService->updateIsSentAndCanBePublished()) {
            $this->subscriberService->resetReceivedForSubscribers();
            $this->monolog->logger->notice(
                'Newsletter sending emails has been done and the flag hasReceived by subcribers set to false.'
            );
        }

        return new Response('...');
    }
}
