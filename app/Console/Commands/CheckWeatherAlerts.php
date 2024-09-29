<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Weather\Services\WeatherAlertService;
use Illuminate\Console\Command;

class CheckWeatherAlerts extends Command
{
    protected $signature = 'weather:check-alerts';
    protected $description = 'Check weather conditions and notify users if necessary';

    private WeatherAlertService $weatherAlertService;

    public function __construct(WeatherAlertService $weatherAlertService)
    {
        parent::__construct();
        $this->weatherAlertService = $weatherAlertService;
    }

    public function handle(): int
    {
        $this->weatherAlertService->checkAndNotify();
        $this->info('Weather alerts checked and notifications sent.');
        
        return self::SUCCESS;
    }
}
