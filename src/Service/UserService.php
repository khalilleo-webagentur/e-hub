<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function getById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function getByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    public function getByToken(string $token): ?User
    {
        return $this->userRepository->findOneBy(['token' => $token]);
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->findBy([], ['id' => 'DESC']);
    }

    public function save(User|UserInterface $model): ?User
    {
        $this->userRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }

    public function encodePassword(string $text): string
    {
        return password_hash($text, PASSWORD_DEFAULT);
    }

    public function isPasswordValid(User|UserInterface $user, string $text): bool
    {
        return password_verify($text, $user->getPassword());
    }
}
