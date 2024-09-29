<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use Random\RandomException;

class UserFactory
{
    /**
     * @throws RandomException
     */
    public static function make(array $attributes = []): User
    {
        return new User(
            id: $attributes['id'] ?? random_int(1, 1000),
            email: $attributes['email'] ?? 'test@example.com',
            city: $attributes['city'] ?? 'London'
        );
    }
}
