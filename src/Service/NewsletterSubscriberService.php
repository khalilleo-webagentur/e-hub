<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Newsletter;
use App\Entity\NewsletterSubscriber;
use App\Entity\Subscriber;
use App\Repository\NewsletterSubscriberRepository;
use DateTime;

final class NewsletterSubscriberService
{
    public function __construct(private readonly NewsletterSubscriberRepository $newsletterSubscriberRepository)
    {
    }

    /**
     * @return NewsletterSubscriber[]
     */
    public function getAll(): array
    {
        return $this->newsletterSubscriberRepository->findAll();
    }

    public function create(Newsletter $newsletter, Subscriber $subscriber): void
    {
        $model = new NewsletterSubscriber();
        $model
            ->setNewsletter($newsletter)
            ->setSubscriber($subscriber)
            ->setSentAt(new DateTime());

        $this->newsletterSubscriberRepository->save($model, true);
    }
}
