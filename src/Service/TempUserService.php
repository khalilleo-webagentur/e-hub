<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\TempUser;
use App\Repository\TempUserRepository;
use DateTime;

final class TempUserService
{
    public function __construct(
        private readonly TempUserRepository $tempUserRepository,
    ) {
    }

    public function getById(int $id): ?TempUser
    {
        return $this->tempUserRepository->find($id);
    }

    public function getByEmail(string $email): ?TempUser
    {
        return $this->tempUserRepository->findOneBy(['email' => $email]);
    }

    public function getByToken(string $token): ?TempUser
    {
        return $this->tempUserRepository->findOneBy(['token' => $token]);
    }

    /**
     * @return TempUser[]
     */
    public function getAll(): array
    {
        return $this->tempUserRepository->findBy([], ['id' => 'DESC']);
    }

    public function save(TempUser $model): ?TempUser
    {
        $this->tempUserRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }

    public function delete(TempUser $model): void
    {
        $this->tempUserRepository->remove($model, true);
    }
}
