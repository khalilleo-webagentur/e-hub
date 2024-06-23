<?php

declare(strict_types=1);

namespace App\Mails\Account;

use App\Mails\AbstractMail;
use App\Mails\MailInterface;
use App\Service\ConfigService;
use App\Service\Dev\Mailer;
use App\Service\MonologService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class ChangeEmailUserMail extends AbstractMail implements MailInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly ConfigService $configService,
        private readonly MonologService $monolog
    ) {
    }

    public function send(...$context): void
    {
        [$username, $userEmail, $token] = $context;

        $email = (new TemplatedEmail())
            ->from(
                new Address(
                    $this->configService->getParameter('no_reply'),
                    $this->configService->getParameter('app_name')
                )
            )
            ->to(new address($userEmail, $username))
            ->subject('Email Verification')
            ->htmlTemplate('mails/account/update_email.html.twig')
            ->context([
                'username' => $username,
                'token' => $token,
            ]);

        Mailer:: catch('/p/u/verify/' . $token);

        $this->mailer->send($email);
    }
}
