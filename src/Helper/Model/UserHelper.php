<?php

declare(strict_types=1);

namespace App\Helper\Model;

final class UserHelper
{
    public const ROLE_PREFIX = 'ROLE_';

    public const ROLE_USER = 'ROLE_USER';

    public const ROLE_EDITOR = 'ROLE_EDITOR';

    public const ROLE_ADMIN = 'ROLE_SUPER_ADMIN';

    public const AVAILABLE_ROLES = [
        self::ROLE_USER,
        self::ROLE_EDITOR,
        self::ROLE_ADMIN
    ];

    public const ROLES = [
        'User' => self::ROLE_USER,
        'Editor' => self::ROLE_EDITOR,
        'Super Admin' => self::ROLE_ADMIN
    ];

    public const DEFAULT_AVATAR = 'avatar.png';

    public const AVATAR_DIR = '/static/uploads/avatar/';
}