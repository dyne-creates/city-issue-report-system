<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Issue Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-violet-500">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Alerts --}}
                    @if (session('success'))
                        <div class="mb-4 rounded-md bg-violet-50 p-4 text-sm text-violet-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Header --}}
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Submitted Reports') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Track all your submitted issues
                            </p>
                        </div>

                        <a href="{{ route('citizen.issues.create') }}"
                           class="inline-flex items-center justify-center px-4 py-2 bg-violet-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-700 transition">
                            + Report New Issue
                        </a>

                    </div>

                    {{-- Filters --}}
                    <form method="POST" action="{{ route('citizen.issues.search') }}"
                          class="mb-6 grid gap-4 md:grid-cols-5">

                        @csrf

                        <div>
                            <x-input-label for="search" :value="__('Keyword')" />
                            <x-text-input id="search" name="search" type="text"
                                :value="$filters['search'] ?? ''"
                                class="mt-1 block w-full"
                                placeholder="Title, category, barangay" />
                        </div>

                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">

                                <option value="">All Statuses</option>

                                @foreach (['reported', 'verified', 'in_progress', 'completed'] as $status)
                                    <option value="{{ $status }}"
                                        {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>
                                        {{ \Illuminate\Support\Str::headline($status) }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div>
                            <x-input-label for="date_from" :value="__('From')" />
                            <x-text-input id="date_from" name="date_from" type="date"
                                :value="$filters['date_from'] ?? ''"
                                class="mt-1 block w-full" />
                        </div>

                        <div>
                            <x-input-label for="date_to" :value="__('To')" />
                            <x-text-input id="date_to" name="date_to" type="date"
                                :value="$filters['date_to'] ?? ''"
                                class="mt-1 block w-full" />
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="px-4 py-2 bg-violet-600 text-white rounded-md text-xs uppercase hover:bg-violet-700 transition">
                                Search
                            </button>

                            <a href="{{ route('citizen.issues.search.reset') }}"
                               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-xs uppercase text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                Reset
                            </a>
                        </div>

                    </form>

                    {{-- Result Count --}}
                    <p class="mb-3 text-sm text-gray-600 dark:text-gray-400">
                        {{ $resultCount }} {{ \Illuminate\Support\Str::plural('result', $resultCount) }} found.
                    </p>

                    {{-- Table --}}
                    <div class="overflow-x-auto relative shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">

                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

                            <thead class="text-xs text-gray-700 uppercase bg-violet-50 dark:bg-violet-900/20 dark:text-gray-300">

                                <tr>
                                    <th class="py-3 px-6">Title</th>
                                    <th class="py-3 px-6">Barangay</th>
                                    <th class="py-3 px-6">Category</th>
                                    <th class="py-3 px-6">Department</th>
                                    <th class="py-3 px-6">Status</th>
                                    <th class="py-3 px-6">Date</th>
                                    <th class="py-3 px-6 text-center">Action</th>
                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                @forelse ($issues as $issue)
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">

                                        <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                            {{ $issue->title }}
                                        </td>

                                        <td class="py-4 px-6">
                                            {{ $issue->barangay_name }}
                                        </td>

                                        <td class="py-4 px-6">
                                            {{ $issue->category_name }}
                                        </td>

                                        <td class="py-4 px-6">
                                            {{ $issue->department_name }}
                                        </td>

                                        <td class="py-4 px-6">
                                            {{ \Illuminate\Support\Str::headline($issue->status) }}
                                        </td>

                                        <td class="py-4 px-6">
                                            {{ \Carbon\Carbon::parse($issue->created_at)->format('F d, Y h:i A') }}
                                        </td>

                                        <td class="py-4 px-6 text-center">

                                            <div class="flex flex-col items-center gap-2">

                                                {{-- Action Links --}}
                                                <div class="flex items-center justify-center gap-4">

                                                    {{-- View --}}
                                                    <a href="{{ route('citizen.issues.show', $issue->id) }}"
                                                        class="text-sm font-medium text-violet-600 hover:text-violet-700 hover:underline">
                                                        View
                                                    </a>

                                                    {{-- Edit --}}
                                                    @if ($issue->status === 'reported')

                                                        <a href="{{ route('citizen.issues.edit', ['issue' => $issue->id,
                                                            'page' => request('page'),
                                                        ]) }}"
                                                            class="text-sm font-medium text-amber-600 hover:text-amber-700 hover:underline">
                                                            Edit
                                                        </a>

                                                    @else

                                                        <span class="text-sm font-medium text-gray-400 cursor-not-allowed">
                                                            Edit
                                                        </span>

                                                    @endif

                                                    {{-- Delete --}}
                                                    <form action="{{ route('citizen.issues.destroy', $issue->id) }}" method="POST" x-data="{ open: false }" class="inline">

                                                        @csrf
                                                        @method('DELETE')

                                                        @if ($issue->status === 'reported')

                                                            <input type="hidden" name="page" value="{{ request('page') }}">

                                                            <button type="button" @click="open = true" class="text-sm font-medium text-red-600 hover:text-red-700 hover:underline">
                                                                Delete
                                                            </button>

                                                            {{-- Delete Confirmation --}}
                                                            <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="display: none;">

                                                                <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

                                                                    <h2 class="text-lg font-semibold text-slate-800">
                                                                        Delete Issue
                                                                    </h2>

                                                                    <p class="mt-2 text-sm text-slate-600">
                                                                        Are you sure you want to delete "<strong>{{ $issue->title }}</strong>"?
                                                                        This action cannot be undone.
                                                                    </p>

                                                                    <div class="mt-6 flex justify-end gap-3">

                                                                        <button type="button" @click="open = false" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                                                                            Cancel
                                                                        </button>

                                                                        <button
                                                                            type="submit"
                                                                            class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                                                            Delete
                                                                        </button>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        @else

                                                            <span class="text-sm font-medium text-gray-400 cursor-not-allowed">
                                                                Delete
                                                            </span>

                                                        @endif

                                                    </form>

                                                </div>

                                                {{-- Status Message --}}
                                                @if ($issue->status === 'completed')

                                                    <p class="text-xs text-gray-500 italic max-w-[190px] leading-5">
                                                        This report can no longer be edited or deleted because it has been completed.
                                                    </p>

                                                @elseif ($issue->status !== 'reported')

                                                    <p class="text-xs text-gray-500 italic max-w-[190px] leading-5">
                                                        This report can no longer be edited or deleted because processing has already begun.
                                                    </p>

                                                @endif

                                            </div>

                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 px-6 text-center text-gray-400 italic">
                                            No reports found.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $issues->links() }}
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>