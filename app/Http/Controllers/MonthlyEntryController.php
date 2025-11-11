<?php

namespace App\Http\Controllers;

use App\Models\MonthlyEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MonthlyEntryController extends Controller
{
    public function index()
    {
        $entries = MonthlyEntry::latest()->paginate(12);

        $stats = [
            'total' => MonthlyEntry::count(),
            'paid' => MonthlyEntry::where('status', 'paid')->count(),
            'overdue' => MonthlyEntry::where('status', 'overdue')->count(),
            'total_paid_amount' => MonthlyEntry::where('status', 'paid')->sum('amount'),
        ];

        // 12 derniers mois
        $availableMonths = collect(range(0, 11))->map(function ($i) {
            return now()->subMonths($i)->format('Y-m');
        });

        return view('monthly_entries.index', compact('entries', 'stats', 'availableMonths'));
    }

    public function create()
    {
        // mêmes mois disponibles
        $availableMonths = collect(range(0, 11))->map(fn($i) => now()->subMonths($i)->format('Y-m'));
        return view('monthly_entries.create', compact('availableMonths'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payer_name' => ['required', 'string', 'max:255'],
            'month' => ['required', 'regex:/^\\d{4}-\\d{2}$/'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['status'] = $request->input('status', 'pending');

        MonthlyEntry::create($validated);

        return redirect()->route('monthly_entries.index')
            ->with('success', __('تم إنشاء الإدخال الشهري بنجاح.'));
    }

    public function edit(MonthlyEntry $monthly_entry)
    {
        $availableMonths = collect(range(0, 11))->map(fn($i) => now()->subMonths($i)->format('Y-m'));
        return view('monthly_entries.edit', [
            'entry' => $monthly_entry,
            'availableMonths' => $availableMonths,
        ]);
    }

    public function update(Request $request, MonthlyEntry $monthly_entry)
    {
        $validated = $request->validate([
            'payer_name' => ['required', 'string', 'max:255'],
            'month' => ['required', 'regex:/^\\d{4}-\\d{2}$/'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:pending,paid,overdue'],
            'notes' => ['nullable', 'string'],
        ]);

        $monthly_entry->update($validated);

        return redirect()->route('monthly_entries.index')
            ->with('success', __('تم تحديث الإدخال الشهري بنجاح.'));
    }

    public function destroy(MonthlyEntry $monthly_entry)
    {
        $monthly_entry->delete();

        return redirect()->route('monthly_entries.index')
            ->with('success', __('تم حذف الإدخال الشهري بنجاح.'));
    }

    public function markAsPaid(MonthlyEntry $monthly_entry)
    {
        $monthly_entry->update(['status' => 'paid']);
        return redirect()->route('monthly_entries.index')
            ->with('success', __('تم وضع علامة على القيد الشهري بأنه مدفوع.'));
    }

    public function markAsOverdue(MonthlyEntry $monthly_entry)  
    {
        $monthly_entry->update(['status' => 'overdue']);
        return redirect()->route('monthly_entries.index')
            ->with('success', __('تم وضع علامة على الإدخال الشهري على أنه متأخر.'));
    }
}