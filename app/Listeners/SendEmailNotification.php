<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\NotificationCreated;
use App\Mail\WeatherAlertMail;
use Illuminate\Support\Facades\Mail;

class SendEmailNotification
{
    public function handle(NotificationCreated $event): void
    {
        if ($event->notification->user()->email()) {
            Mail::to($event->notification->user()->email()->value())->send(
                new WeatherAlertMail($event->notification->message())
            );
        }
    }
}
