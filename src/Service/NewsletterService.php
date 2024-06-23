<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Newsletter;
use App\Repository\NewsletterRepository;
use App\Traits\RandomTokenGeneratorTrait;
use DateTime;

final class NewsletterService
{
    use RandomTokenGeneratorTrait;

    public function __construct(private readonly NewsletterRepository $newsletterRepository)
    {
    }

    public function getById(int $id): ?Newsletter
    {
        return $this->newsletterRepository->find($id);
    }

    public function getOneByTokenAndCanBePublished(string $token, bool $canBePublished): ?Newsletter
    {
        return $this->newsletterRepository->findOneBy(['token' => $token, 'canBePublished' => $canBePublished]);
    }

    public function getByCanBePublished(): ?Newsletter
    {
        return $this->newsletterRepository->findOneBy(['canBePublished' => 1]);
    }

    /**
     * @return Newsletter[]
     */
    public function getAll(): array
    {
        return $this->newsletterRepository->findAllOrderByDesc();
    }

    public function updateIsSentAndCanBePublished(): bool
    {
        $isDone = false;

        if ($newsletter = $this->getByCanBePublished()) {
            $newsletter
                ->setIsSent(true)
                ->setCanBePublished(false)
                ->setUpdatedAt(new DateTime());

            $this->newsletterRepository->createorupdate($newsletter, true);

            $isDone = true;
        }

        return $isDone;
    }

    public function updateCanBePublished(Newsletter $newsletter, bool $canBePublished): Newsletter
    {
        $newsletter
            ->setCanBePublished($canBePublished)
            ->setUpdatedAt(new DateTime());

        $this->newsletterRepository->createorupdate($newsletter, true);

        return $newsletter;
    }

    public function deactiveAllOthersNewsletter(): void
    {
        if ($newsletter = $this->getByCanBePublished()) {
            $this->updateCanBePublished($newsletter, false);
        }
    }

    public function updateToken(Newsletter $newsletter, string $token): Newsletter
    {
        $newsletter
            ->setToken($token)
            ->setUpdatedAt(new DateTime());

        $this->newsletterRepository->createorupdate($newsletter, true);

        return $newsletter;
    }

    public function createOrupdate(
        ?Newsletter $newsletter,
        string $title,
        string $content,
        bool $canBePublished = false,
        bool $isSent = false,
        ?string $token = null
    ): Newsletter {

        if (empty($newsletter)) {
            $newsletter = new Newsletter();
            $token = $this->getRandomTokenOnlyLetters();
        }

        $newsletter
            ->setTitle($title)
            ->setContent($content)
            ->setToken($token)
            ->setCanBePublished($canBePublished)
            ->setIsSent($isSent)
            ->setToken($token)
            ->setUpdatedAt(new DateTime());

        $this->newsletterRepository->createorupdate($newsletter, true);

        return $newsletter;
    }

    public function delete(Newsletter $newsletter): void
    {
        $this->newsletterRepository->remove($newsletter, true);
    }
}