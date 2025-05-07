<?php

declare(strict_types=1);

namespace App\Service\Import;

use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class ImportAbstract
{
    protected function jsonDecode(UploadedFile|array|null $file): mixed
    {
        $data = file_get_contents($file->getRealPath());

        return json_decode($data, true);
    }

    protected function isExtensionAllowed(UploadedFile|array $file): bool
    {
        $extension = $file->getClientOriginalExtension();

        return !empty($extension) && in_array($extension, ['csv', 'json'], true);
    }
}