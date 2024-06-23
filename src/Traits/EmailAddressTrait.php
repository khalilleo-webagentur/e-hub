<?php

declare(strict_types=1);

namespace App\Traits;

use Exception;

trait EmailAddressTrait
{
    private function getNameFromEmailAddress(string $email): string
    {
        try {
            $prefix = strrpos($email, '@');
            $prefix = substr($email, 0, $prefix);
            $name = str_replace(['.', '_'], ' ', $prefix);
        } catch (Exception $e) {
            $name = '';
        }

        return $name;
    }

    private function getDomainFromEmailAddress(string $email): string
    {
        $temp = explode('@', $email);
        
        return array_pop($temp);
    }
}