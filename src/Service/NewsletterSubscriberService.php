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
    public function __construct(
        private readonly NewsletterSubscriberRepository $newsletterSubscriberRepository
    ) {
    }

    /**
     * @return NewsletterSubscriber[]
     */
    public function getAllByNewsletter(Newsletter $newsletter): array
    {
        return $this->newsletterSubscriberRepository->findBy(['newsletter' => $newsletter]);
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

    public function deleteByNewsletter(Newsletter $newsletter): void
    {
        foreach ($this->getAllByNewsletter($newsletter) as $row) {
            $this->delete($row);
        }
    }

    public function delete(NewsletterSubscriber $model): void
    {
        $this->newsletterSubscriberRepository->remove($model, true);
    }
}
