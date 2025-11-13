<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Entrances to Dar Al-Quran') }}
            </h2>
            <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Statistiques globales -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-blue-600 text-sm font-medium">{{ __('Montant total') }}</div>
                            <div class="text-2xl font-bold text-blue-800">{{ number_format($stats['total_amount'], 2) }} DH</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-green-600 text-sm font-medium">{{ __('Paid') }}</div>
                            <div class="text-2xl font-bold text-green-800">{{ $stats['paid'] }}</div>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <div class="text-red-600 text-sm font-medium">{{ __('Overdue') }}</div>
                            <div class="text-2xl font-bold text-red-800">{{ $stats['overdue'] }}</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <div class="text-yellow-600 text-sm font-medium">{{ __('On hold') }}</div>
                            <div class="text-2xl font-bold text-yellow-800">{{ $stats['pending'] }}</div>
                        </div>
                    </div>

                    <!-- Tableau des paiements -->
                    <div class="overflow-x-auto">
                        @php($rtl = app()->getLocale() === 'ar')
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Etudiant') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Month') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Payment Date') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Payment Method') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Notes') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($payments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('students.show', $payment->student) }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $payment->student->first_name }} {{ $payment->student->last_name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ \Carbon\Carbon::createFromFormat('Y-m', $payment->month)->locale($rtl ? 'ar' : app()->getLocale())->translatedFormat('F Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm text-gray-900">{{ number_format($payment->amount, 2) }} DH</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm text-gray-900">{{ optional($payment->payment_date)->format('d/m/Y') ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm text-gray-900">{{ $payment->payment_method ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($payment->status == 'paid')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('Paid') }}</span>
                                            @elseif($payment->status == 'overdue')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('Overdue') }}</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ __('On hold') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="text-sm text-gray-900">{{ $payment->notes ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex justify-center space-x-3">
                                                <a href="{{ route('students.payments.index', $payment->student) }}" class="text-indigo-600 hover:text-indigo-900" title="{{ __('Voir paiements étudiant') }}" aria-label="{{ __('Voir paiements étudiant') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <span class="sr-only">{{ __('Voir paiements étudiant') }}</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">{{ __('Aucun paiement enregistré.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>