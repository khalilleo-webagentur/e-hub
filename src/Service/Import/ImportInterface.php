<?php

declare(strict_types=1);

namespace App\Service\Import;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImportInterface
{
    public function fromJson(UploadedFile|array|null $file): int;

    public function fromCsv(UploadedFile|array|null $file): int;
}