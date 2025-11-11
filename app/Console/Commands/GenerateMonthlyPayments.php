<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GenerateMonthlyPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:generate-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère une entrée de paiement mensuel "En retard" pour chaque étudiant au début du mois.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $month = Carbon::now()->format('Y-m');
        $createdCount = 0;

        $amount = 50; // Montant fixe mensuel

        Student::query()->chunkById(200, function ($students) use (&$createdCount, $month, $amount) {
            foreach ($students as $student) {
                $exists = Payment::query()
                    ->where('student_id', $student->id)
                    ->where('month', $month)
                    ->exists();

                if ($exists) {
                    continue;
                }

                Payment::create([
                    'student_id' => $student->id,
                    'month' => $month,
                    'amount' => $amount,
                    'payment_date' => null,
                    'payment_method' => null,
                    'status' => 'overdue',
                    'notes' => null,
                ]);
                $createdCount++;
            }
        });

        $this->info("Paiements mensuels créés: {$createdCount} pour le mois {$month}");
        return self::SUCCESS;
    }
}