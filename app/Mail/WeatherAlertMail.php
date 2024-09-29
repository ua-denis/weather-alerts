<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Mail\Mailable;

class WeatherAlertMail extends Mailable
{
    public string $messageContent;

    public function __construct(string $messageContent)
    {
        $this->messageContent = $messageContent;
    }

    public function build(): self
    {
        return $this->subject('Weather Alert')->view('emails.weather_alert');
    }
}
