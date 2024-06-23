<?php

declare(strict_types=1);

namespace App\Mails;

use App\Exception\InvalidConfigArgumentException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

interface MailInterface
{
    /**
     * @throws InvalidConfigArgumentException
     * @throws TransportExceptionInterface
     */
    public function send(...$contents): void;
}