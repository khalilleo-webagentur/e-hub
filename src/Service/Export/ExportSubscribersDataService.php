<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\Subscriber;
use App\Helper\ExportHelper;
use App\Service\SubscriberService;
use Symfony\Component\Security\Core\User\UserInterface;

final class ExportSubscribersDataService extends ExporterAbstract implements ExporterInterface
{
    public function __construct(
        private readonly SubscriberService $subscriberService,
    ) {
    }

    public function asJson(UserInterface $user): bool|string
    {
        return $this->jsonEncode(ExportHelper::DEFAULT_NEWSLETTER_SUBSCRIBERS_FILE_NAME, $this->data($user));
    }

    public function emailsAsJson(UserInterface $user): bool|string
    {
        return $this->jsonEncode(ExportHelper::DEFAULT_NEWSLETTER_SUBSCRIBERS_EMAILS_FILE_NAME, $this->emailsData($user));
    }

    private function data(UserInterface $user): array
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
        }, $this->subscriberService->getAllByUser($user));
    }

    private function emailsData(UserInterface $user): array
    {
        return array_map(static function (Subscriber $entity) {
            return [
                'email' => $entity->getEmail(),
            ];
        }, $this->subscriberService->getActiveSubscribersByUser($user));
    }
}