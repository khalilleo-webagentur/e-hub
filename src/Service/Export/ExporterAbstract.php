<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Helper\ExportHelper;

abstract class ExporterAbstract
{
    protected function jsonEncode(string $fileName, array $data): bool|string
    {
        $this->prepareJsonHeader($fileName);

        return json_encode($data, JSON_PRETTY_PRINT);
    }

    private function prepareJsonHeader(string $fileName): void
    {
        $file = 'Exported_' . $fileName . '_' . date(ExportHelper::DEFAULT_DATETIME_FORMAT_IN_FILE_NAME) . '.json';

        header("Content-Type: application/json");
        header("Content-Disposition: attachment; filename=" . htmlspecialchars($file));
        header("Pragma: no-cache");
        header("Expires: 0");
    }
}