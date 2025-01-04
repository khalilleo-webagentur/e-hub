<?php

declare(strict_types=1);

namespace App\Service\Import;

use App\Dto\Newsletter\SubscribersImportDto;
use App\Helper\AppHelper;
use App\Service\SubscriberService;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class SubscriberImportService extends ImportAbstruct implements ImportInterface
{
    private $subscribed = false;
    private $seberator = ';';

    public function __construct(private readonly SubscriberService $subscriberService)
    {
    }

    public function fromJson(UploadedFile|array|null $file): int
    {
        if (empty($file)) {
            return 0;
        }

        $subscribers = [];

        if ($items = $this->jsonDecode($file)) {
            foreach ($items as $item) {
                $subscribers[] = new SubscribersImportDto($item);
            }
        }

        $count = 0;

        foreach ($subscribers as $subscriber) {

            $email = $subscriber->getEmail();

            if ($email) {
                $this->subscriberService->import($subscriber->getName(), $email, $this->subscribed);
                $count++;
            }
        }

        return $count;
    }

    public function fromCsv(UploadedFile|array|null $file): int
    {
        if (empty($file)) {
            return 0;
        }

        $file = fopen($file->getRealPath(), 'rb');

        $data = [];

        $header = [
            'name',
            'email'
        ];

        while (($item = fgetcsv($file, 10000, $this->seberator)) !== false) {
            if (!isset($item[0]) || in_array($item[0], $header, true)) {
                continue;
            }

            $data[] = $item;
        }

        $count = 0;

        try {
            foreach ($data as [$name, $email]) {

                if ($email) {
                    $this->subscriberService->import($name, $email, $this->subscribed);
                    $count++;
                }
            }
        } catch (Exception $e) {
            return 0;
        }

        return $count;
    }

    public function setSubscribed(bool $subscribed): static
    {
        $this->subscribed = $subscribed;

        return $this;
    }

    public function setSeperator(string $seberator): static
    {
        $this->seberator = $seberator;

        return $this;
    }
}