<?php

declare(strict_types=1);

namespace App\Domains\Notification\Entities;

use App\Domains\User\Entities\User;
use App\Events\NotificationCreated;
use Illuminate\Support\Facades\Log;

final class Notification
{
    private User $user;
    private string $type;
    private string $message;

    public function __construct(User $user, string $type, string $message)
    {
        $this->user = $user;
        $this->type = $type;
        $this->message = $message;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function send(): void
    {
        Log::info('Sending notification...', ['user' => $this->user, 'type' => $this->type]);

        NotificationCreated::dispatch($this);
    }
}
