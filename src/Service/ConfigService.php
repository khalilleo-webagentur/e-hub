<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ConfigService
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly MonologService $monolog
    ) {
    }

    public function getParameter(string $key): string
    {
        try {
            return $this->container->getParameter($key);
        } catch (Exception $e) {
            $this->monolog->logger->critical($e->getTraceAsString());
        }
    }
}
