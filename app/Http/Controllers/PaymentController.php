<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{
    /**
     * Afficher la liste des paiements pour un étudiant
     */
    public function index(Student $student)
    {
        $payments = $student->payments()->orderBy('month', 'desc')->get();
        
        // Générer les mois disponibles pour les 12 derniers mois
        $availableMonths = [];
        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $monthLabel = Carbon::now()->subMonths($i)->format('F Y');
            $availableMonths[$month] = $monthLabel;
        }
        
        return view('payments.index', compact('student', 'payments', 'availableMonths'));
    }

    /**
     * Afficher le formulaire de création d'un paiement
     */
    public function create(Student $student)
    {
        return view('payments.create', compact('student'));
    }

    /**
     * Enregistrer un nouveau paiement
     */
    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'month' => 'required|string|size:7|unique:payments,month,NULL,id,student_id,' . $student->id,
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500'
        ], [
            'month.unique' => 'Un paiement pour ce mois existe déjà pour cet étudiant.'
        ]);

        // Montant fixe 50 DH
        $validated['amount'] = 50;

        $payment = new Payment($validated);
        $payment->student_id = $student->id;
        $payment->status = 'paid';
        $payment->save();

        return redirect()->route('students.payments.index', $student)
            ->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Afficher le formulaire d'édition d'un paiement
     */
    public function edit(Student $student, Payment $payment)
    {
        return view('payments.edit', compact('student', 'payment'));
    }

    /**
     * Mettre à jour un paiement
     */
    public function update(Request $request, Student $student, Payment $payment)
    {
        $validated = $request->validate([
            'month' => 'required|string|size:7|unique:payments,month,' . $payment->id . ',id,student_id,' . $student->id,
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'status' => 'required|in:pending,paid,overdue',
            'notes' => 'nullable|string|max:500'
        ], [
            'month.unique' => 'Un paiement pour ce mois existe déjà pour cet étudiant.'
        ]);

        // Forcer le montant fixe 50 DH
        $validated['amount'] = 50;

        $payment->update($validated);

        return redirect()->route('students.payments.index', $student)
            ->with('success', 'Paiement modifié avec succès.');
    }

    /**
     * Supprimer un paiement
     */
    public function destroy(Student $student, Payment $payment)
    {
        $payment->delete();

        return redirect()->route('students.payments.index', $student)
            ->with('success', 'Paiement supprimé avec succès.');
    }

    /**
     * Marquer un paiement comme payé
     */
    public function markAsPaid(Student $student, Payment $payment)
    {
        $payment->update([
            'status' => 'paid',
            'payment_date' => now()
        ]);

        return redirect()->route('students.payments.index', $student)
            ->with('success', 'Paiement marqué comme payé.');
    }

    /**
     * Marquer un paiement comme en retard
     */
    public function markAsOverdue(Student $student, Payment $payment)
    {
        $payment->update(['status' => 'overdue']);

        return redirect()->route('students.payments.index', $student)
            ->with('success', 'Paiement marqué comme en retard.');
    }
}
