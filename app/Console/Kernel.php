<?php

namespace App\Console;

use App\Console\Commands\GenerateMonthlyPayments;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Exécuter la génération le 1er de chaque mois à 00:00
        $schedule->command('payments:generate-monthly')->monthlyOn(1, '00:00');

        // Par sécurité, exécuter quotidiennement à 00:05 et ne créer que pour le mois courant
        $schedule->command('payments:generate-monthly')->dailyAt('00:05');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}