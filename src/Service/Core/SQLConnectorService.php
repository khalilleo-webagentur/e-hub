<?php

declare(strict_types=1);

namespace App\Service\Core;

use Khalilleo\Connector\PdoConnect;
use Khalilleo\Connector\SqlOperation;

final class SQLConnectorService 
{
    public function target(string $targetTable): ?SqlOperation
    {
        if (isset($_ENV['DATABASE_URL']) && isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'dev') {

            $conn = mb_split(':', $_ENV['DATABASE_URL']);
            $temp = explode('@', $conn[2]);

            $host = $temp[1];
            $username = str_replace('//', '', $conn[1]);
            $password = $temp[0];

            $temp = explode('/', strtok($conn[3], '?'));
            $port = $temp[0];
            $dbName = $temp[1];

            $conn = new PdoConnect($host, $dbName, $username, $password);

            return new SqlOperation($conn->pdo, $targetTable);
        }

        return null;
    }
}