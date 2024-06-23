<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\Newsletter;
use App\Helper\ExportHelper;
use App\Service\NewsletterService;

final class ExportNewslettersService extends ExporterAbstruct implements ExporterInterface
{
    public function __construct(
        private readonly NewsletterService $newsletterService,
    ) {
    }

    public function asJson(): bool|string
    {
        return $this->jsonEncode(ExportHelper::DEFAULT_NEWSLETTER_FILE_NAME, $this->data());
    }

    private function data(): array
    {
        return array_map(static function (Newsletter $entity) {
            return [
                'title' => $entity->getTitle(),
                'content' => $entity->getContent(),
                'token' => $entity->getToken(),
                'can_be_published' => $entity->isCanBePublished(),
                'is_sent' => $entity->isSent(),
                'modified' => ($entity->getUpdatedAt())->format(ExportHelper::DEFAULT_DATETIME_FORMAT),
                'created' => ($entity->getCreatedAt())->format(ExportHelper::DEFAULT_DATETIME_FORMAT)
            ];
        }, $this->newsletterService->getAll());
    }
}