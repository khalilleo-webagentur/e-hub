<?php

declare(strict_types=1);

namespace App\Dto\Newsletter;

final class SubscribersImportDto
{
    private string $name;
    private string $email;

    public function __construct(array $data)
    {
        $this->name = isset($data['name']) ? (string)$data['name'] : '';
        $this->email = isset($data['email']) ? $data['email'] : '';
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }
}