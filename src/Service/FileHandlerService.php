<?php

declare(strict_types=1);

namespace App\Service;

final class FileHandlerService
{
    public function getFile(string $dir, string $fileName, bool $withPath = false): ?string
    {
        if (empty($dir) || empty($fileName)) {
            return null;
        }

        foreach ($this->getFiles($dir) as $fileInFolder) {
            if ($withPath === true && $fileInFolder === $fileName) {
                return $dir . '/' . $fileInFolder;
            }

            if ($fileInFolder === $fileName) {
                return $fileInFolder;
            }
        }

        return null;
    }

    public function unlinkFile(string $dir, string $fileName): bool
    {
        if (empty($dir) || empty($fileName)) {
            return false;
        }

        if ($this->isFileExistsInDir($dir, $fileName)) {
            unlink($dir . '/' . $fileName);
            return true;
        }

        return false;
    }

    public function isImageExtensionAllowed(string $extension): bool
    {
        if (empty($extension)) {
            return false;
        }

        return in_array($extension, ['png', 'jpg', 'jpeg'], true);
    }

    public function isFileExistsInDir(string $dir, string $fileName): bool
    {
        if (empty($dir) || empty($fileName)) {
            return false;
        }

        return in_array($fileName, $this->getFiles($dir), true);
    }

    private function getFiles(string $dir): array
    {
        if (empty($dir)) {
            return [];
        }

        $entries = [];

        if (is_dir($dir) && $handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                $entries[] = $entry;
            }

            closedir($handle);
        }

        return array_diff($entries, ['.', '..', '.gitignore']);
    }
}
