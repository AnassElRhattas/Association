<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Monthly Entry') }}
            </h2>
            <a href="{{ route('monthly_entries.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                {{ __('Back to entries') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Messages d'erreur -->
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulaire -->
                    <form method="POST" action="{{ route('monthly_entries.store') }}" class="max-w-2xl">
                        @csrf

                        <div class="space-y-6">
                            <!-- Payer Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Payer Name') }}</label>
                                <input type="text" name="payer_name" value="{{ old('payer_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('payer_name') border-red-500 @enderror" required>
                                @error('payer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Month -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Month') }}</label>
                                <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('month') border-red-500 @enderror" required>
                                    @foreach($availableMonths as $m)
                                        <option value="{{ $m }}" {{ old('month', date('Y-m')) === $m ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                                @error('month')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Amount (DH)') }}</label>
                                <input type="number" name="amount" step="0.01" min="0" value="{{ old('amount', 0) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror" required>
                                @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Payment Date') }}</label>
                                <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('payment_date') border-red-500 @enderror" required>
                                @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Payment Method') }}</label>
                                <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('Select a method') }}</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>{{ __('Bank transfer') }}</option>
                                    <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>{{ __('Check') }}</option>
                                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>{{ __('Card') }}</option>
                                </select>
                                @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Status') }}</label>
                                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                                    <option value="overdue" {{ old('status') === 'overdue' ? 'selected' : '' }}>{{ __('Overdue') }}</option>
                                </select>
                                @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="flex justify-end space-x-3 mt-6">
                            <a href="{{ route('monthly_entries.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>