<?php

declare(strict_types=1);

namespace App\Service\Core;

use Symfony\Contracts\Translation\TranslatorInterface;

final class TranslatorService
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function trans(string $message, ?string $param = null, ?string $locale = 'en'): string
    {
        if (empty($param)) {
            return $this->translator->trans($message, ['locale' => $locale]);
        }

        return sprintf($this->translator->trans($message, ['locale' => $locale]), $param);
    }
}
