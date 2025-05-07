<?php

declare(strict_types=1);

namespace App\Service\Export;

use Symfony\Component\Security\Core\User\UserInterface;

interface ExporterInterface
{
    public function asJson(UserInterface $user): string|bool;
}