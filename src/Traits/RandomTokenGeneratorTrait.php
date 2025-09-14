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
            return (int)str_shuffle('345987');
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

    private function getRandomApiToken(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Convert to hexadecimal and format as 8-4-4-4-12
        $hex = bin2hex($data);
        return substr($hex, 0, 8) . '-' .
            substr($hex, 8, 4) . '-' .
            substr($hex, 12, 4) . '-' .
            substr($hex, 16, 4) . '-' .
            substr($hex, 20, 12);
    }
}