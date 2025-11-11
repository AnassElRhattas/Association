<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Rendre la date de paiement nullable afin de créer des entrées "en retard" sans date
            $table->date('payment_date')->nullable()->change();

            // Assurer l'unicité du paiement par étudiant et par mois
            $table->unique(['student_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Supprimer l'index unique si présent
            $table->dropUnique(['student_id', 'month']);

            // Revenir à une date non nullable
            $table->date('payment_date')->nullable(false)->change();
        });
    }
};