<?php

declare(strict_types=1);

namespace App\Mails;

use App\Exception\InvalidConfigArgumentException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

abstract class AbstractMail
{
    /**
     * @throws InvalidConfigArgumentException
     * @throws TransportExceptionInterface
     */
    abstract protected function send(...$contents): void;
}