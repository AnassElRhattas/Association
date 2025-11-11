<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-md transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            {{ __('Back to Students List') }}
                        </a>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Edit Form -->
                    <form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Student Information Section -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('Student Information') }}
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- First Name -->
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                     {{ __('First Name') }}*
                                    </label>
                                    <input type="text" 
                                           name="first_name" 
                                           id="first_name" 
                                           value="{{ old('first_name', $student->first_name) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           required>
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                     {{ __('Last Name') }}*
                                    </label>
                                    <input type="text" 
                                           name="last_name" 
                                           id="last_name" 
                                           value="{{ old('last_name', $student->last_name) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           required>
                                </div>

                                <!-- Birth Date -->
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                     {{ __('Date of Birth') }}*
                                    </label>
                                    <input type="date" 
                                           name="birth_date" 
                                           id="birth_date" 
                                           value="{{ old('birth_date', \Carbon\Carbon::parse($student->birth_date)->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           required>
                                </div>

                                <!-- Registration Date -->
                                <div>
                                    <label for="registration_date" class="block text-sm font-medium text-gray-700 mb-2">
                                     {{ __('Registration Date') }}*
                                    </label>
                                    <input type="date" 
                                           name="registration_date" 
                                           id="registration_date" 
                                           value="{{ old('registration_date', \Carbon\Carbon::parse($student->registration_date)->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!-- Current Files Section -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ __('Current Files') }}
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Current Profile Photo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Current Profile Photo') }}
                                    </label>
                                    @if($student->profile_photo)
                                        <div class="flex items-center space-x-4">
                                            <img src="{{ asset('storage/' . $student->profile_photo) }}" 
                                                 alt="Profile Photo" 
                                                 class="w-20 h-20 rounded-full object-cover border-2 border-blue-300">
                                            <span class="text-sm text-green-600 font-medium">{{ __('Photo available') }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center space-x-4">
                                            <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ __('No photo available') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Current Birth Certificate -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Current Birth Certificate') }}
                                    </label>
                                    @if($student->birth_certificate)
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ asset('storage/' . $student->birth_certificate) }}" 
                                               target="_blank" 
                                               class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                {{ __('View Certificate') }}
                                            </a>
                                            <span class="text-sm text-green-600 font-medium">{{ __('Certificate available') }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center space-x-4">
                                            <div class="px-3 py-2 bg-gray-200 text-gray-500 rounded-md">
                                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                {{ __('No certificate') }}
                                            </div>
                                            <span class="text-sm text-gray-500">{{ __('No certificate available') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- File Uploads Section -->
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                {{ __('Update Files') }}
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- New Profile Photo -->
                                <div>
                                    <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('New Profile Photo') }}
                                    </label>
                                    <input type="file" 
                                           name="profile_photo" 
                                           id="profile_photo" 
                                           accept="image/*"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ __('Leave empty to keep current photo. Accepted formats: JPG, PNG, GIF') }}
                                    </p>
                                </div>

                                <!-- New Birth Certificate -->
                                <div>
                                    <label for="birth_certificate" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('New Birth Certificate') }}
                                    </label>
                                    <input type="file" 
                                           name="birth_certificate" 
                                           id="birth_certificate" 
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ __('Leave empty to keep current certificate. Accepted formats: PDF, JPG, PNG') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6">
                            <a href="{{ route('students.index') }}" 
                               class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-md transition duration-200">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ __('Update Student') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>