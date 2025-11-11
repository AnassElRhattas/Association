<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Students List') }}
            </h2>
            <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('Add New Student') }}
            </a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- En-tête -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">
                                Modifier le paiement de {{ $student->first_name }} {{ $student->last_name }}
                            </h2>
                            <p class="text-gray-600 mt-1">Modifier les détails du paiement mensuel</p>
                        </div>
                        <a href="{{ route('students.payments.index', $student) }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            Retour aux paiements
                        </a>
                    </div>

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
                    <form action="{{ route('students.payments.update', [$student, $payment]) }}" method="POST" class="max-w-2xl">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Mois -->
                            <div>
                                <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Mois du paiement *</label>
                                <input type="month"
                                    name="month"
                                    id="month"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('month') border-red-500 @enderror"
                                    value="{{ old('month', $payment->month) }}"
                                    required>
                                @error('month')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Montant (fixe) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Montant (fixe)</label>
                                <div class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-800">50 DH</div>
                                <p class="mt-1 text-xs text-gray-500">Le montant des paiements étudiants est fixé à 50 DH.</p>
                            </div>

                            <!-- Date de paiement -->
                            <div>
                                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Date de paiement *</label>
                                <input type="date"
                                    name="payment_date"
                                    id="payment_date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('payment_date') border-red-500 @enderror"
                                    value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}"
                                    required>
                                @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Méthode de paiement -->
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement</label>
                                <select name="payment_method"
                                    id="payment_method"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Sélectionner une méthode</option>
                                    <option value="espèces" {{ old('payment_method', $payment->payment_method) == 'espèces' ? 'selected' : '' }}>Espèces</option>
                                    <option value="virement bancaire" {{ old('payment_method', $payment->payment_method) == 'virement bancaire' ? 'selected' : '' }}>Virement bancaire</option>
                                    <option value="chèque" {{ old('payment_method', $payment->payment_method) == 'chèque' ? 'selected' : '' }}>Chèque</option>
                                    <option value="carte bancaire" {{ old('payment_method', $payment->payment_method) == 'carte bancaire' ? 'selected' : '' }}>Carte bancaire</option>
                                </select>
                                @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Statut -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                                <select name="status"
                                    id="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                                    required>
                                    <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="paid" {{ old('status', $payment->status) == 'paid' ? 'selected' : '' }}>Payé</option>
                                    <option value="overdue" {{ old('status', $payment->status) == 'overdue' ? 'selected' : '' }}>En retard</option>
                                </select>
                                @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (optionnel)</label>
                                <textarea name="notes"
                                    id="notes"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                                    placeholder="Ajoutez des notes supplémentaires...">{{ old('notes', $payment->notes) }}</textarea>
                                @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="flex justify-end space-x-3 mt-6">
                            <a href="{{ route('students.payments.index', $student) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                                Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                                Mettre à jour le paiement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>