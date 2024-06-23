<?php

declare(strict_types=1);

namespace App\Service\Dev;

use RuntimeException;

final class Mailer
{
    public static function catch(mixed $content): void
    {
        if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'dev') {
            file_put_contents(self::getFileName(), $content);
        }
    }

    private static function getFileName(): string
    {
        $path = __DIR__ . "/../../../storage/mailer/";

        if (!file_exists($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        return $path . 'mailer.html';
    }
}
