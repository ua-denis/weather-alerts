<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use App\Domains\User\ValueObjects\Email;

class User
{
    public int $id;
    private Email $email;
    private string $city;

    public function __construct(int $id, string $email, string $city)
    {
        $this->id = $id;
        $this->email = new Email($email);
        $this->city = $city;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function city(): string
    {
        return $this->city;
    }
}
