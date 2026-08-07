<x-app-layout>
    @section('title', 'Admin')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- FILTERS --}}
            <div
                class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">
                <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('Filter Report') }}</h3>

                    <form method="GET" action="{{ route('admin.reports.index') }}" id="reportFilterForm">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                            {{-- Status checkboxes --}}
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                <div class="space-y-1">
                                    @foreach (['reported', 'verified', 'in_progress', 'completed'] as $status)
                                        <label class="flex items-center gap-2 text-sm">
                                            <input type="checkbox" name="statuses[]" value="{{ $status }}"
                                                class="rounded border-gray-300 text-violet-600 focus:ring-violet-500"
                                                @checked(in_array($status, request('statuses', [])))>
                                            {{ ucwords(str_replace('_', ' ', $status)) }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Department --}}
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                                <select name="department_id"
                                    class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm focus:ring-violet-500 focus:border-violet-500">
                                    <option value="">All Departments</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}" @selected(request('department_id') == $dept->id)>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Barangay --}}
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Barangay</label>
                                <select name="barangay_id"
                                    class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm focus:ring-violet-500 focus:border-violet-500">
                                    <option value="">All Barangays</option>
                                    @foreach ($barangays as $brgy)
                                        <option value="{{ $brgy->id }}" @selected(request('barangay_id') == $brgy->id)>
                                            {{ $brgy->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter mode toggle --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Period
                                    Type</label>
                                <div class="flex gap-4 text-sm">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="filter_mode" value="custom"
                                            class="text-violet-600 focus:ring-violet-500"
                                            @checked(request('filter_mode', 'custom') === 'custom')
                                            onchange="toggleFilterMode()">
                                        Custom Range
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="filter_mode" value="quick"
                                            class="text-violet-600 focus:ring-violet-500"
                                            @checked(request('filter_mode') === 'quick') onchange="toggleFilterMode()">
                                        Quick Period
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Custom range fields --}}
                        <div id="customRangeFields" class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date
                                    From</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                    class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm focus:ring-violet-500 focus:border-violet-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date
                                    To</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                    class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm focus:ring-violet-500 focus:border-violet-500">
                            </div>
                        </div>

                        {{-- Quick period fields --}}
                        <div id="quickPeriodFields" class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4 hidden">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                                <select name="year"
                                    class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm focus:ring-violet-500 focus:border-violet-500">
                                    <option value="" selected disabled>Select Year</option>
                                    @foreach ($years as $y)
                                        <option value="{{ $y }}" @selected(request('year') == $y)>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                                <select name="month"
                                    class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm focus:ring-violet-500 focus:border-violet-500">
                                    <option value="">Any Month</option>

                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" @selected(request('month') == $m)>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3 mt-6">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-violet-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:opacity-90 transition">
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.reports.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                Reset
                            </a>
                            <a href="{{ route('admin.reports.pdf', request()->query()) }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white text-sm font-medium rounded-lg transition ml-auto">
                                Export PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CHARTS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                    class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">
                    <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Issues by Status</h3>
                        <canvas id="statusChart" height="200"></canvas>
                    </div>
                </div>

                <div
                    class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">
                    <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Avg. Satisfaction Rating
                            by Department</h3>
                        <canvas id="deptChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            {{-- TABLE --}}
            <div
                class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">
                <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Filtered Results</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-violet-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Title
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Status
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">
                                        Barangay</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">
                                        Category</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">
                                        Department</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Citizen
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">
                                        Reported</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">
                                        Resolved</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Rating
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($issues as $issue)
                                    <tr class="text-gray-900 dark:text-gray-100 hover:bg-violet-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-4 py-2">{{ $issue->title }}</td>
                                        <td class="px-4 py-2">
                                            <span @class([
                                                'inline-block px-2 py-1 rounded-full text-xs font-medium',
                                                'bg-yellow-100 text-yellow-700' => $issue->status === 'reported',
                                                'bg-blue-100 text-blue-700' => $issue->status === 'verified',
                                                'bg-orange-100 text-orange-700' => $issue->status === 'in_progress',
                                                'bg-green-100 text-green-700' => $issue->status === 'completed',
                                            ])>
                                                {{ ucwords(str_replace('_', ' ', $issue->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">{{ $issue->barangay_name }}</td>
                                        <td class="px-4 py-2">{{ $issue->category_name }}</td>
                                        <td class="px-4 py-2">{{ $issue->department_name }}</td>
                                        <td class="px-4 py-2">{{ $issue->citizen_name }}</td>
                                        <td class="px-4 py-2">
                                            {{ \Carbon\Carbon::parse($issue->created_at)->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $issue->resolved_at ? \Carbon\Carbon::parse($issue->resolved_at)->format('M d, Y') : '—' }}
                                        </td>
                                        <td class="px-4 py-2">{{ $issue->feedback_rating ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                            No issues match the selected filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $issues->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        function toggleFilterMode() {
            const mode = document.querySelector('input[name="filter_mode"]:checked').value;
            document.getElementById('customRangeFields').classList.toggle('hidden', mode !== 'custom');
            document.getElementById('quickPeriodFields').classList.toggle('hidden', mode !== 'quick');
        }
        document.addEventListener('DOMContentLoaded', toggleFilterMode);

        const statusLabels = @json($statusLabels);

        const statusData = @json($statusData);
        const deptLabels = @json($deptLabels);
        const deptData = @json($deptData);
        
        new Chart(document.getElementById('statusChart'), {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                    label: 'Number of Issues',
                    data: statusData,
                    backgroundColor: ['#facc15', '#3b82f6', '#fb923c', '#22c55e'],
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });

        new Chart(document.getElementById('deptChart'), {
            type: 'pie',
            data: {
                labels: deptLabels,
                datasets: [{
                    label: 'Avg Rating',
                    data: deptData,
                    backgroundColor: ['#8b5cf6', '#a78bfa', '#c4b5fd', '#7c3aed', '#6d28d9', '#4c1d95'],
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>
</x-app-layout>