<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        '\App\Console\Commands\Deploy',
        '\App\Console\Commands\Import',
        '\App\Console\Commands\ImportCountries',
        '\App\Console\Commands\ImportCities',
        '\App\Console\Commands\ImportPoints',
        '\App\Console\Commands\ImportAgentPoints',
        '\App\Console\Commands\GeocodePoints',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
