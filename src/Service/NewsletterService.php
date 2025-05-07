<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Newsletter;
use App\Repository\NewsletterRepository;
use App\Traits\RandomTokenGeneratorTrait;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

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
    public function getAllByUser(UserInterface $user): array
    {
        return $this->newsletterRepository->findAllByUserOrderByDesc($user);
    }

    /**
     * @return Newsletter[]
     */
    public function getAll(): array
    {
        return $this->newsletterRepository->findAllOrderByDesc();
    }

    public function updateStatusIsSentAndCanBePublished(Newsletter $newsletter): void
    {
        $newsletter
            ->setIsSent(true)
            ->setCanBePublished(false);

        $this->save($newsletter);
    }

    public function updateCanBePublished(Newsletter $newsletter, bool $canBePublished): Newsletter
    {
        $this->save($newsletter->setCanBePublished($canBePublished));

        return $newsletter;
    }

    public function deactivateAllOthersNewsletter(): void
    {
        if ($newsletter = $this->getByCanBePublished()) {
            $this->updateCanBePublished($newsletter, false);
        }
    }

    public function updateToken(Newsletter $newsletter, string $token): Newsletter
    {
        $this->save($newsletter->setToken($token));

        return $newsletter;
    }

    public function createOrUpdate(
        ?Newsletter $newsletter,
        UserInterface $user,
        string      $title,
        string      $content,
        bool        $canBePublished = false,
        bool        $isSent = false,
        ?string     $token = null
    ): Newsletter
    {

        if (empty($newsletter)) {
            $newsletter = new Newsletter();
            $token = $this->getRandomTokenOnlyLetters();
        }

        $newsletter
            ->setUser($user)
            ->setTitle($title)
            ->setContent($content)
            ->setToken($token)
            ->setCanBePublished($canBePublished)
            ->setIsSent($isSent)
            ->setToken($token)
            ->setUpdatedAt(new DateTime());

        $this->save($newsletter, true);

        return $newsletter;
    }

    public function save(Newsletter $newsletter): void
    {
        $this->newsletterRepository->save(
            $newsletter->setUpdatedAt(new DateTime()),
            true
        );
    }

    public function delete(Newsletter $newsletter): void
    {
        $this->newsletterRepository->remove($newsletter, true);
    }
}
