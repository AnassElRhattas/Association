<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <div class="flex items-center space-x-2 space-x-reverse">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">تسجيل التقدم في الحفظ</h2>
            </div>
            <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-200">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                رجوع إلى قائمة الطلاب
            </a>
        </div>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-right">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-right">
                            <ul class="list-disc pr-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('memorization_progress.store') }}" class="space-y-6"
                          x-data="{
                            // Students
                            students: {{ $students->toJson() }},
                            studentQuery: '',
                            filteredStudents: [],
                            studentsOpen: false,
                            selectedStudentId: '{{ old('student_id') }}',
                            selectedStudentName: '',
                            // Verses
                            verseStart: Number('{{ old('verse_start') }}') || null,
                            verseEnd: Number('{{ old('verse_end') }}') || null,
                            get verseValid() { return (this.verseStart ?? 0) <= (this.verseEnd ?? -1); },
                            get formValid() { return !!this.selectedStudentId && !!this.verseStart && !!this.verseEnd && this.verseValid; },
                            // Presets
                            presets: [
                                { label: '1–10', start: 1, end: 10 },
                                { label: '11–20', start: 11, end: 20 },
                                { label: '21–30', start: 21, end: 30 },
                                { label: '31–40', start: 31, end: 40 },
                            ],
                            init() {
                                this.filteredStudents = this.students;
                                const match = this.students.find(s => String(s.id) === String(this.selectedStudentId));
                                this.selectedStudentName = match ? match.name : '';
                            },
                            filterStudents() {
                                const q = this.studentQuery.trim().toLowerCase();
                                this.filteredStudents = q ? this.students.filter(s => s.name.toLowerCase().includes(q)) : this.students;
                                this.studentsOpen = true;
                            },
                            selectStudent(s) {
                                this.selectedStudentId = s.id;
                                this.selectedStudentName = s.name;
                                this.studentsOpen = false;
                            },
                            applyPreset(p) { this.verseStart = p.start; this.verseEnd = p.end; }
                          }"
                          x-init="init()">
                        @csrf

                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Student -->
                            <div class="col-span-1" x-data>
                                <label class="block text-sm font-medium text-gray-800 mb-2 text-right">الطالب</label>
                                <input type="text" x-model="studentQuery" @input="filterStudents()" placeholder="ابحث عن الطالب بالاسم" class="w-full mb-2 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"/>
                                <div class="relative">
                                    <button type="button" @click="studentsOpen = !studentsOpen" class="w-full text-right px-3 py-2 border border-gray-300 rounded-lg bg-white">
                                        <span x-text="selectedStudentName || 'اختر الطالب'"></span>
                                    </button>
                                    <div x-show="studentsOpen" @click.away="studentsOpen=false" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow max-h-48 overflow-auto">
                                        <template x-for="s in filteredStudents" :key="s.id">
                                            <div @click="selectStudent(s)" class="px-3 py-2 text-right hover:bg-blue-50 cursor-pointer" x-text="s.name"></div>
                                        </template>
                                        <div x-show="filteredStudents.length===0" class="px-3 py-2 text-right text-gray-500">لا نتائج</div>
                                    </div>
                                </div>
                                <input type="hidden" name="student_id" :value="selectedStudentId">
                                @error('student_id')
                                <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sura -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-800 mb-2 text-right">السورة</label>
                                <select name="sura_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 @error('sura_name') border-red-500 @enderror" required>
                                    <option value="">اختر السورة</option>
                                    @foreach($suras as $name)
                                        <option value="{{ $name }}" {{ old('sura_name') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('sura_name')
                                <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Verse Start -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-800 mb-2 text-right">من الآية</label>
                                <input type="number" name="verse_start" min="1" x-model.number="verseStart" value="{{ old('verse_start') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 @error('verse_start') border-red-500 @enderror" required>
                                <p class="mt-1 text-xs text-gray-500 text-right">رقم الآية بالأرقام</p>
                                @error('verse_start')
                                <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Verse End -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-800 mb-2 text-right">إلى الآية</label>
                                <input type="number" name="verse_end" min="1" x-model.number="verseEnd" value="{{ old('verse_end') }}" :class="['w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2', verseValid ? 'border-gray-300 focus:ring-blue-600 focus:border-blue-600' : 'border-red-500 focus:ring-red-600 focus:border-red-600']" required>
                                <p class="mt-1 text-xs" :class="verseValid ? 'text-gray-500' : 'text-red-600'" class="text-right">رقم الآية بالأرقام</p>
                                @error('verse_end')
                                <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-800 mb-2 text-right">التاريخ</label>
                                <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 @error('date') border-red-500 @enderror" required>
                                @error('date')
                                <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                                @enderror
                            </div>
                            </div>
                            </div>
                            <!-- Presets de plage d'ayat -->
                            <div class="mt-4 flex flex-wrap gap-2 justify-end">
                                <template x-for="preset in presets" :key="preset.label">
                                    <button type="button" @click="applyPreset(preset)" class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                                        <span x-text="preset.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Optional note (collapsed) -->
                        <div x-data="{ open: false }" class="mt-4 sm:px-6">
                            <button type="button" @click="open = !open" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-4-4"/></svg>
                                إضافة ملاحظة (اختياري)
                            </button>
                            <div x-show="open" class="mt-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <label class="block text-sm font-medium text-gray-800 mb-2 text-right">ملاحظة</label>
                                <textarea name="note" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 @error('note') border-red-500 @enderror">{{ old('note') }}</textarea>
                                @error('note')
                                <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end mt-6 space-x-3 space-x-reverse sm:px-6">
                            <button type="reset" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200">تفريغ</button>
                            <button type="submit" :disabled="!formValid" :class="formValid ? 'opacity-100' : 'opacity-60 cursor-not-allowed'" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-200 shadow-sm">
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                حفظ
                            </button>
                        </div>
                    </form>

                    <!-- Recent entries: last 10 -->
                    <div class=" sm:px-6 mt-10 mb-6" dir="rtl">
                        <h3 class="text-lg font-semibold mb-4 text-right">آخر 10 سجلات في التقدم</h3>
                        @if(isset($recent) && $recent->count())
                            <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg">
                                <table class="min-w-full table-auto">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">الطالب</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">السورة</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">من</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">إلى</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">التاريخ</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">ملاحظة</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recent as $r)
                                            <tr>
                                                <td class="px-4 py-2 text-center text-sm text-gray-900">
                                                    {{ optional($r->student)->first_name }} {{ optional($r->student)->last_name }}
                                                </td>
                                                @php(
                                                    $palette = ['bg-indigo-50 text-indigo-700','bg-pink-50 text-pink-700','bg-green-50 text-green-700','bg-blue-50 text-blue-700','bg-yellow-50 text-yellow-700','bg-purple-50 text-purple-700','bg-red-50 text-red-700','bg-teal-50 text-teal-700']
                                                )
                                                @php($idx = crc32($r->sura_name) % count($palette))
                                                <td class="px-4 py-2 text-center text-sm"><span class="inline-block px-3 py-1 rounded-full {{ $palette[$idx] }}">{{ $r->sura_name }}</span></td>
                                                <td class="px-4 py-2 text-center text-sm text-gray-900">{{ $r->verse_start }}</td>
                                                <td class="px-4 py-2 text-center text-sm text-gray-900">{{ $r->verse_end }}</td>
                                                <td class="px-4 py-2 text-center text-sm text-gray-900">{{ \Carbon\Carbon::parse($r->date)->format('Y-m-d') }}</td>
                                                <td class="px-4 py-2 text-center text-sm text-gray-700">{{ \Illuminate\Support\Str::limit($r->note, 60) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="px-4 py-3">{{ $recent->links() }}</div>
                            </div>
                        @else
                            <p class="text-right text-gray-600">لا توجد سجلات حديثة.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>