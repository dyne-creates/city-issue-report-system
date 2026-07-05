<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- STATS --}}
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-5">

                @foreach ($counts as $label => $count)
                    <div
                        class="relative rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-violet-100 dark:border-gray-700 overflow-hidden">

                        {{-- purple top accent --}}
                        <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>

                        <div class="p-5">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                {{ \Illuminate\Support\Str::headline($label) }}
                            </p>

                            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $count }}
                            </p>
                        </div>
                    </div>
                @endforeach

            </div>

            {{-- RECENT REPORTS --}}
            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">

                <div class="p-6">

                    {{-- header --}}
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Recent Reports
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Track your latest submitted city issue reports.
                            </p>
                        </div>

                        <a href="{{ route('citizen.issues.create') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white
                                  bg-violet-600 hover:bg-violet-700 transition shadow-sm">
                            Report Issue
                        </a>

                    </div>

                    {{-- table --}}
                    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">

                        <table class="min-w-full text-sm">

                            <thead
                                class="bg-violet-50 dark:bg-violet-900/20 text-gray-700 dark:text-gray-300 text-xs uppercase">
                                <tr>
                                    <th class="py-3 px-6 text-left">Title</th>
                                    <th class="py-3 px-6 text-left">Barangay</th>
                                    <th class="py-3 px-6 text-left">Category</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-left">Date</th>
                                    <th class="py-3 px-6 text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                                @forelse ($recentIssues as $issue)
                                    <tr class="hover:bg-violet-50/40 dark:hover:bg-gray-700/40 transition">

                                        <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                            {{ $issue->title }}
                                        </td>

                                        <td class="py-4 px-6 text-gray-600 dark:text-gray-300">
                                            {{ $issue->barangay_name }}
                                        </td>

                                        <td class="py-4 px-6 text-gray-600 dark:text-gray-300">
                                            {{ $issue->category_name }}
                                        </td>

                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                                    @if($issue->status === 'reported') bg-gray-100 text-gray-700
                                                    @elseif($issue->status === 'verified') bg-blue-100 text-blue-700
                                                    @elseif($issue->status === 'in_progress') bg-amber-100 text-amber-700
                                                    @else bg-emerald-100 text-emerald-700
                                                    @endif">
                                                {{ \Illuminate\Support\Str::headline($issue->status) }}
                                            </span>
                                        </td>

                                        <td class="py-4 px-6 text-gray-500">
                                            {{ \Carbon\Carbon::parse($issue->created_at)->format('M d, Y') }}
                                        </td>

                                        <td class="py-4 px-6 text-center">
                                            <a href="{{ route('citizen.issues.show', $issue->id) }}"
                                                class="text-sm font-medium text-violet-600 hover:text-violet-800">
                                                View
                                            </a>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-10 text-center text-gray-400 italic">
                                            No reports yet.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>
                    </div>

                    <div class="mt-5">
                        <a href="{{ route('citizen.issues.index') }}"
                            class="text-sm font-medium text-violet-600 hover:text-violet-800">
                            View all reports →
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>