<?php

namespace App\EventSubscriber;

use App\Service\MonologService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MonologService $monolog
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [['logException', 0]],
        ];
    }

    public function logException(ExceptionEvent $event): void
    {
        $message = $event->getThrowable()->getMessage();

        $this->monolog->logger->warning($message);
    }
}
