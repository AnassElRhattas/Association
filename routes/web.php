<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MonthlyEntryController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Student routes
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    
    // Payment routes (suppression de la création manuelle)
    Route::get('/students/{student}/payments', [PaymentController::class, 'index'])->name('students.payments.index');
    Route::get('/students/{student}/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('students.payments.edit');
    Route::put('/students/{student}/payments/{payment}', [PaymentController::class, 'update'])->name('students.payments.update');
    Route::delete('/students/{student}/payments/{payment}', [PaymentController::class, 'destroy'])->name('students.payments.destroy');
    Route::post('/students/{student}/payments/{payment}/mark-paid', [PaymentController::class, 'markAsPaid'])->name('students.payments.mark-paid');
    Route::post('/students/{student}/payments/{payment}/mark-overdue', [PaymentController::class, 'markAsOverdue'])->name('students.payments.mark-overdue');

    // Monthly entries routes (general monthly payments not tied to students)
    Route::get('/monthly-entries', [MonthlyEntryController::class, 'index'])->name('monthly_entries.index');
    Route::get('/monthly-entries/create', [MonthlyEntryController::class, 'create'])->name('monthly_entries.create');
    Route::post('/monthly-entries', [MonthlyEntryController::class, 'store'])->name('monthly_entries.store');
    Route::get('/monthly-entries/{monthly_entry}/edit', [MonthlyEntryController::class, 'edit'])->name('monthly_entries.edit');
    Route::put('/monthly-entries/{monthly_entry}', [MonthlyEntryController::class, 'update'])->name('monthly_entries.update');
    Route::delete('/monthly-entries/{monthly_entry}', [MonthlyEntryController::class, 'destroy'])->name('monthly_entries.destroy');
    Route::post('/monthly-entries/{monthly_entry}/mark-paid', [MonthlyEntryController::class, 'markAsPaid'])->name('monthly_entries.mark-paid');
    Route::post('/monthly-entries/{monthly_entry}/mark-overdue', [MonthlyEntryController::class, 'markAsOverdue'])->name('monthly_entries.mark-overdue');
});

require __DIR__.'/auth.php';
