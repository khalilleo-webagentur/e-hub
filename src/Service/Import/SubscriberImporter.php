<?php

declare(strict_types=1);

namespace App\Service\Import;

use App\Dto\Newsletter\SubscribersImportDto;
use App\Entity\User;
use App\Service\SubscriberService;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

final class SubscriberImporter extends ImportAbstract implements ImportInterface
{
    private bool $subscribed = false;
    private string $separator = ';';

    public function __construct(
        private readonly SubscriberService $subscriberService
    ) {
    }

    public function fromJson(UploadedFile|array|null $file, UserInterface|User $user): int
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
                $this->subscriberService->import($user, $subscriber->getName(), $email, $this->subscribed);
                $count++;
            }
        }

        return $count;
    }

    public function fromCsv(UploadedFile|array|null $file, UserInterface|User $user): int
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

        while (($item = fgetcsv($file, 10000, $this->separator)) !== false) {
            if (!isset($item[0]) || in_array($item[0], $header, true)) {
                continue;
            }

            $data[] = $item;
        }

        $count = 0;

        try {
            foreach ($data as [$name, $email]) {

                if ($email) {
                    $this->subscriberService->import($user, $name, $email, $this->subscribed);
                    $count++;
                }
            }
        } catch (Exception $e) {
            return 0;
        }

        return $count;
    }

    public function setSubscribed(bool $subscribed): SubscriberImporter
    {
        $this->subscribed = $subscribed;

        return $this;
    }

    public function setSeparator(string $separator): SubscriberImporter
    {
        $this->separator = $separator;

        return $this;
    }
}