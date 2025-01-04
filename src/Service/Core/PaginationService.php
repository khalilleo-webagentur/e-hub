<?php

declare(strict_types=1);

namespace App\Service\Core;

use App\Helper\Model\UserSettingConfig;

final class PaginationService
{
    public function paginate(array $data, int $pageNumberFromRequest, int $limit, int $adjacent = 2): array
    {
        $total = count($data);
        $totalPages = (int)ceil($total / $limit);
        $pageNumberFromRequest = $this->getPage($pageNumberFromRequest);
        $firstPage = $this->getFirstPage($totalPages, $adjacent, $pageNumberFromRequest);

        return [
            'total' => $total,
            'limit' => $limit,
            'page' => $pageNumberFromRequest,
            'totalPages' => $totalPages,
            'Newer' => $firstPage,
            'defualtLimit' => 8,
            'limitToResetPagination' => 32,
            'padginationOptions' => [
                8,
                16,
                24,
                32,
                1000
            ],
        ];
    }

    public function getPage(int $pageInRequest): int
    {
        return $pageInRequest !== 0 ? $pageInRequest : 1;
    }

    public function getFirstPage(int $totalPages, int $adjacent, int $page): int
    {
        if (($page - $adjacent) > 1) {
            if (($page + $adjacent) < $totalPages) {
                $firstPage = ($page - $adjacent);
            } else {
                $firstPage = ($totalPages - (1 + ($adjacent * 2)));
            }
        } else {
            $firstPage = 1;
        }

        return $firstPage;
    }

    public function getOffset(int $pageInRequest, int $limit): int
    {
        return ($pageInRequest !== 0) ? ($limit * ($pageInRequest - 1)) : 0;
    }
}