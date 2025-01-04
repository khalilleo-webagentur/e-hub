<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final class FileUploaderService
{
    public function __construct(
        private string                    $targetDirectory,
        private readonly SluggerInterface $slugger,
        private readonly MonologService   $monolog
    ) {
    }

    public function upload(UploadedFile $file, ?string $filename = null): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename, '_');

        $fileName = $filename ?? $safeFilename . '_' . date('Ymd_His') . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            $this->monolog->logger->error($e->getMessage());
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function setTargetDirectory(string $targetDirectory): FileUploaderService
    {
        $this->targetDirectory = $targetDirectory;

        return $this;
    }
}
