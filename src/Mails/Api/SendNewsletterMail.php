<?php

declare(strict_types=1);

namespace App\Mails\Api;

use App\Mails\AbstractMail;
use App\Mails\MailInterface;
use App\Service\ConfigService;
use App\Service\Dev\Mailer as DevMailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class SendNewsletterMail extends AbstractMail implements MailInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly ConfigService $configService
    ) {
    }

    public function send(...$context): void
    {
        [$subName, $subEmail, $subToken, $newsTitle, $newsContent] = $context;

        $subject = $newsTitle . ' - ' . $this->configService->getParameter('app_name') . ' Newsletter';

        $email = (new TemplatedEmail())
            ->from(
                new Address(
                    $this->configService->getParameter('no_reply'),
                    $this->configService->getParameter('app_name')
                )
            )
            ->to(new address($subEmail, $subName))
            ->subject($subject)
            ->htmlTemplate('mails/api/send_newsletter_content.html.twig')
            ->context([
                'username' => $subName,
                'title' => $newsTitle,
                'content' => $newsContent,
                'token' => $subToken,
                'showUnSubscriptionLink' => true,
            ]);

        DevMailer::catch(sprintf('Message has been sent to %s right now.', $subEmail));

        $this->mailer->send($email);
    }
}