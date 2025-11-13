<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Details') }}
            </h2>
            <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <h3 class="text-lg font-semibold mb-3">{{ __('Basic Information') }}</h3>
                            <ul class="space-y-2 text-gray-700">
                                <li><span class="font-medium">{{ __('Name') }}:</span> {{ $student->first_name }} {{ $student->last_name }}</li>
                                <li><span class="font-medium">{{ __('Born') }}:</span> {{ \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') }}</li>
                                <li><span class="font-medium">{{ __('Registered') }}:</span> {{ \Carbon\Carbon::parse($student->registration_date)->format('d/m/Y') }}</li>
                            </ul>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <h3 class="text-lg font-semibold mb-3">{{ __('Documents') }}</h3>
                            <ul class="space-y-2 text-gray-700">
                                <li>
                                    <span class="font-medium">{{ __('Profile Photo') }}:</span>
                                    @if($student->profile_photo)
                                        <img src="{{ Storage::url($student->profile_photo) }}" alt="{{ __('Profile Photo') }}" class="h-24 w-24 rounded object-cover border border-gray-200 mt-2">
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </li>
                                <li>
                                    <span class="font-medium">{{ __('Birth Certificate') }}:</span>
                                    @if($student->birth_certificate)
                                        <a href="{{ Storage::url($student->birth_certificate) }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ __('View Certificate') }}</a>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Memorization Acceleration Chart -->
                    <div class="mt-8">
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold">{{ __('Memorization Progress') }}</h3>
                                    <span class="text-sm text-gray-500">{{ __('Weekly, cumulative or acceleration view') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="px-3 py-1 text-sm rounded border border-gray-300 hover:bg-gray-100" data-mode="weekly">{{ __('Hebdomadaire') }}</button>
                                    <button type="button" class="px-3 py-1 text-sm rounded border border-gray-300 hover:bg-gray-100" data-mode="cumulative">{{ __('Cumulatif') }}</button>
                                    <button type="button" class="px-3 py-1 text-sm rounded border border-gray-300 hover:bg-gray-100" data-mode="acceleration">{{ __('Accélération') }}</button>
                                </div>
                            </div>
                            <div style="height: 300px;">
                                <canvas id="memorizationAccelChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-3">{{ __('Recent Memorization Progress') }}</h3>
                        @if($progress->count())
                            <div class="overflow-x-auto bg-white border border-gray-200 rounded-md">
                                <table class="min-w-full table-auto">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">{{ __('Sura') }}</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">{{ __('Verses') }}</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">{{ __('Date') }}</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">{{ __('Note') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($progress as $p)
                                            <tr>
                                                <td class="px-4 py-2 text-center text-sm text-gray-900">{{ $p->sura_name }}</td>
                                                <td class="px-4 py-2 text-center text-sm text-gray-900">{{ $p->verse_start }} ← {{ $p->verse_end }}</td>
                                                <td class="px-4 py-2 text-center text-sm text-gray-900">{{ \Carbon\Carbon::parse($p->date)->format('Y-m-d') }}</td>
                                                <td class="px-4 py-2 text-center text-sm text-gray-700">{{ \Illuminate\Support\Str::limit($p->note, 60) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-600">{{ __('No memorization records found for this student.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<!-- Chart.js and chart init -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        const labels = @json($chartData['labels'] ?? []);
        const weeklyCounts = @json($chartData['counts'] ?? []);
        const weeklyAccel = @json($chartData['accel'] ?? []);

        const cumulativeCounts = weeklyCounts.reduce((acc, v, i) => {
            acc.push((acc[i-1] || 0) + (v || 0));
            return acc;
        }, []);

        const canvas = document.getElementById('memorizationAccelChart');
        if (!canvas || labels.length === 0) return;
        const ctx = canvas.getContext('2d');

        let chartMode = 'weekly';
        let chart;

        function gradient(color) {
            const g = ctx.createLinearGradient(0, 0, 0, canvas.height);
            g.addColorStop(0, color.replace('1)', '0.25)'));
            g.addColorStop(1, color.replace('1)', '0)'));
            return g;
        }

        function datasetForMode(mode) {
            if (mode === 'weekly') {
                return [{
                    type: 'line',
                    label: '{{ __('Verses per week') }}',
                    data: weeklyCounts,
                    borderColor: 'rgba(99, 102, 241, 1)', // indigo
                    backgroundColor: gradient('rgba(99, 102, 241, 1)'),
                    tension: 0.35,
                    fill: true,
                    pointRadius: 2,
                    borderWidth: 2,
                }];
            }
            if (mode === 'cumulative') {
                return [{
                    type: 'line',
                    label: '{{ __('Cumulative verses') }}',
                    data: cumulativeCounts,
                    borderColor: 'rgba(16, 185, 129, 1)', // emerald
                    backgroundColor: gradient('rgba(16, 185, 129, 1)'),
                    tension: 0.35,
                    fill: true,
                    pointRadius: 2,
                    borderWidth: 2,
                }];
            }
            return [{
                type: 'line',
                label: '{{ __('Acceleration (Δ week)') }}',
                data: weeklyAccel,
                borderColor: 'rgba(234, 179, 8, 1)', // amber
                backgroundColor: gradient('rgba(234, 179, 8, 1)'),
                tension: 0.35,
                fill: true,
                pointRadius: 2,
                borderWidth: 2,
                borderDash: [6, 4],
            }];
        }

        function render(mode) {
            if (chart) chart.destroy();
            chart = new Chart(ctx, {
                data: {
                    labels: labels,
                    datasets: datasetForMode(mode)
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        }
                    },
                    plugins: {
                        legend: { display: true, position: 'top' },
                        tooltip: { enabled: true }
                    }
                }
            });
        }

        // Initialize
        render(chartMode);

        // Wire buttons
        const container = canvas.closest('.p-4');
        if (container) {
            container.querySelectorAll('button[data-mode]').forEach(btn => {
                btn.addEventListener('click', () => {
                    chartMode = btn.getAttribute('data-mode');
                    render(chartMode);
                    container.querySelectorAll('button[data-mode]').forEach(b => b.classList.remove('bg-gray-200'));
                    btn.classList.add('bg-gray-200');
                });
            });
            // Mark default
            const defaultBtn = container.querySelector('button[data-mode="weekly"]');
            if (defaultBtn) defaultBtn.classList.add('bg-gray-200');
        }
    })();
</script>