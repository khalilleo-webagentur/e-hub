<?php

declare(strict_types=1);

namespace App\Mails\Admin;

use App\Mails\AbstractMail;
use App\Mails\MailInterface;
use App\Service\ConfigService;
use App\Service\Dev\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class ContactFormNewMessageMail extends AbstractMail implements MailInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly ConfigService   $configService
    ) {
    }

    public function send(...$context): void
    {
        $webmasterName = $this->configService->getParameter('webmasterName');

        $webmasterEmail = $this->configService->getParameter('webmasterEmail');

        $email = (new TemplatedEmail())
            ->from(
                new Address(
                    $this->configService->getParameter('no_reply'),
                    $this->configService->getParameter('app_name')
                )
            )
            ->to(new address($webmasterEmail, $webmasterName))
            ->subject('E-Hub QA: New Message Arrived')
            ->htmlTemplate('mails/admin/contact_form_new_message.html.twig')
            ->textTemplate('mails/admin/contact_form_new_message.txt.twig')
            ->context([
                'username' => $webmasterName
            ]);

        Mailer:: catch(sprintf('Admin has been informed. Email was sent to [%s]', $webmasterEmail));

        $this->mailer->send($email);
    }
}
