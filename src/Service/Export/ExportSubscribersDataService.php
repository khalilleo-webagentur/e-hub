<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\Subscriber;
use App\Helper\ExportHelper;
use App\Service\SubscriberService;

final class ExportSubscribersDataService extends ExporterAbstruct implements ExporterInterface
{
    public function __construct(
        private readonly SubscriberService $subscriberService,
    ) {
    }

    public function asJson(): bool|string
    {
        return $this->jsonEncode(ExportHelper::DEFAULT_NEWSLETTER_SUBSCRIBERS_FILE_NAME, $this->data());
    }

    public function emailsAsJson(): bool|string
    {
        return $this->jsonEncode(ExportHelper::DEFAULT_NEWSLETTER_SUBSCRIBERS_EMAILS_FILE_NAME, $this->emailsData());
    }

    private function data(): array
    {
        return array_map(static function (Subscriber $entity) {
            return [
                'name' => $entity->getName(),
                'email' => $entity->getEmail(),
                'token' => $entity->getToken(),
                'is_subscribed' => $entity->isSubscribed(),
                'has_received' => $entity->isHasReceived(),
                'modified' => ($entity->getUpdatedAt())->format(ExportHelper::DEFAULT_DATETIME_FORMAT),
                'created' => ($entity->getCreatedAt())->format(ExportHelper::DEFAULT_DATETIME_FORMAT)
            ];
        }, $this->subscriberService->getAll());
    }

    private function emailsData(): array
    {
        return array_map(static function (Subscriber $entity) {
            return [
                'email' => $entity->getEmail(),
            ];
        }, $this->subscriberService->getActiveSubscribers());
    }
}