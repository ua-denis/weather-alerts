<?php

declare(strict_types=1);

namespace App\Domains\User\ValueObjects;

use Illuminate\Support\Facades\Log;

final class Email
{
    private string $email;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = 'invalid@example.com';
            Log::error("Invalid email address: {$email}");
        }
        $this->email = $email;
    }

    public function value(): string
    {
        return $this->email;
    }
}
