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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('month'); // Mois du paiement (ex: "2024-01")
            $table->decimal('amount', 10, 2); // Montant du paiement
            $table->date('payment_date'); // Date du paiement
            $table->string('payment_method')->nullable(); // Méthode de paiement
            $table->string('status')->default('pending'); // pending, paid, overdue
            $table->text('notes')->nullable(); // Notes additionnelles
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
