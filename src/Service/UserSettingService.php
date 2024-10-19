<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\UserSetting;
use App\Repository\UserSettingRepository;
use DateTime;

final class UserSettingService
{
    public function __construct(
        private readonly UserSettingRepository $userSettingRepository,
    ) {}

    public function getById(int $id): ?UserSetting
    {
        return $this->userSettingRepository->find($id);
    }

    public function getByUser(User $user): ?UserSetting
    {
        $userSetting = $this->userSettingRepository->findOneBy(['user' => $user]);

        if (!$userSetting) {
            $userSetting = new UserSetting();
            $this->save($userSetting->setUser($user));
        }

        return $userSetting;
    }

    /**
     * @return UserSetting[]
     */
    public function getAll(): array
    {
        return $this->userSettingRepository->findBy([], ['id' => 'DESC']);
    }

    public function save(UserSetting $model): ?UserSetting
    {
        $this->userSettingRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }
}
