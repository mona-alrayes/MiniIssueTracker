<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\GenerateContributionReports::class
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('reports:contributions')
        //     ->weekly()
        //     ->mondays()
        //     ->at('08:00');

        $schedule->command('reports:contributions')
            ->daily()
            ->at('08:00')
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
