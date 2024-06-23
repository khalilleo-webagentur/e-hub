<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\Security\Core\User\UserInterface;

final class PersonalDataExport
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function asJson(UserInterface|User $user): bool|string
    {
        $this->prepareJsonHeader($user->getName());

        return json_encode($this->exportByUser($user), JSON_PRETTY_PRINT);
    }

    private function exportByUser(UserInterface|User $user): array
    {
        return [
            'account' => $this->getUserTableData($user),
        ];
    }

    private function getUserTableData(UserInterface|User $user): array
    {
        $row = $this->userService->getById($user->getId());
        $result = [];

        if (null !== $row) {
            $result = [
                '_user_name' => $row->getName(),
                '_email_address' => $row->getEmail(),
                '_user_role' => strtolower(str_replace('_', '-', $row->getRoles()[0])),
                '_is_account_verified' => $row->isVerified(),
                '_is_account_deleted' => $row->isDeleted(),
                '_last_updated_at' => ($row->getUpdatedAt())->format('Y-m-d H:i:s'),
                '_account_created_at' => ($row->getCreatedAt())->format('Y-m-d H:i:s'),
            ];
        }

        return $result;
    }

    private function prepareJsonHeader(string $username): void
    {
        $file = ucfirst($username) . '_Personal_Data_' . date('Ymd_His') . '.json';

        header("Content-Type: application/json");
        header("Content-Disposition: attachment; filename=" . htmlspecialchars($file));
        header("Pragma: no-cache");
        header("Expires: 0");
    }
}
