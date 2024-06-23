<?php

declare(strict_types=1);

namespace App\Service;

// use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class TwoFactorAuthService
{
    public function __construct(
        // public readonly SessionInterface $session,
    ) {
    }

    // public function setCurrentUserSession(): void
    // {
    //     $this->cleanUp();

    //     $name = self::USER_SESSION_KEY . self::$userID;

    //     $this->session->set($name, sha1($name));
    // }

    // public function getCurrentUserSession(): string
    // {
    //     $this->cleanUp();

    //     $name = self::USER_SESSION_KEY . self::$userID;

    //     $this->session->set($name, sha1($name));

    //     return self::$session->get($name);
    // }

    // public static function isCurrentUserSessionCorrect(): bool
    // {
    //     $name = self::USER_SESSION_KEY . self::$userID;

    //     $name = self::$session->get($name);

    //     return sha1(self::$userID) === $name;
    // }

    // public static function cleanUp(): void
    // {
    //     self::$session->remove(self::USER_SESSION_KEY . self::$userID);
    // }
}
