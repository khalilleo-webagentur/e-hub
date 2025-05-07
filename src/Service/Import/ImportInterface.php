<?php

declare(strict_types=1);

namespace App\Service\Import;

use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

interface ImportInterface
{
    public function fromJson(UploadedFile|array|null $file, UserInterface|User $user): int;

    public function fromCsv(UploadedFile|array|null $file, UserInterface|User $user): int;
}