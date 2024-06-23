<?php

declare(strict_types=1);

namespace App\Traits;

use Exception;

trait RandomTokenGeneratorTrait
{
    private function getRandomToken(int $length = 64): string
    {
        try {
            $token = bin2hex(random_bytes($length));
        } catch (Exception $e) {
            $token = sha1(str_shuffle('A1B2C3D4e5f6g7h8i9G'));
        }

        return $token;
    }

    private function getOtp(): int
    {
        try {
            return random_int(111111, 999999);
        } catch (Exception $e) {
            return (int) str_shuffle('345987');
        }
    }

    private function generateRandomPassword(int $length = 8): string
    {
        $smallLetters = "abcdefghijklmnopqrstuvwxyz";
        $shuffleSmall = str_shuffle($smallLetters);
        $capitalLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffleCapital = str_shuffle($capitalLetters);

        $shuffle = substr($shuffleCapital, 0, 5)
            . str_shuffle('$_+%*?=@#~!')[1]
            . str_shuffle('23456789')[1]
            . substr($shuffleSmall, 0, 9);

        return str_shuffle(substr($shuffle, 0, $length));
    }

    private function getRandomTokenOnlyLetters(int $length = 32): string
    {
        $letters = str_shuffle('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');

        return str_shuffle(substr($letters, 0, $length));
    }

    private function getRandomApiToken(int $length = 32): string
    {
        $letters = str_shuffle('abcdefghijk_l12345_mnopqrstuvwxyz' . 'ABCDEFGHIJKLM_6789_NOPQRSTUVWXYZ');

        return rtrim(str_shuffle(substr($letters, 0, $length)), '_');
    }
}