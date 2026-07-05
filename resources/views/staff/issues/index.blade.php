<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Assigned Issues') }}
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
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Department Issue Queue
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Update only status and remarks for assigned reports.
                        </p>
                    </div>

                    {{-- Filters --}}
                    <form method="GET" action="{{ route('staff.issues.index') }}"
                          class="mb-6 grid gap-4 md:grid-cols-5">

                        <div>
                            <x-input-label for="search" :value="__('Keyword')" />
                            <x-text-input id="search" name="search" type="text"
                                :value="request('search')"
                                class="mt-1 block w-full"
                                placeholder="Title, citizen, barangay" />
                        </div>

                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">

                                <option value="">All Statuses</option>

                                @foreach (['reported', 'verified', 'in_progress', 'completed'] as $status)
                                    <option value="{{ $status }}"
                                        {{ request('status') === $status ? 'selected' : '' }}>
                                        {{ \Illuminate\Support\Str::headline($status) }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div>
                            <x-input-label for="date_from" :value="__('From')" />
                            <x-text-input id="date_from" name="date_from" type="date"
                                :value="request('date_from')" class="mt-1 block w-full" />
                        </div>

                        <div>
                            <x-input-label for="date_to" :value="__('To')" />
                            <x-text-input id="date_to" name="date_to" type="date"
                                :value="request('date_to')" class="mt-1 block w-full" />
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="px-4 py-2 bg-violet-600 text-white rounded-md text-xs uppercase hover:bg-violet-700 transition">
                                Search
                            </button>

                            <a href="{{ route('staff.issues.index') }}"
                               class="px-4 py-2 border rounded-md text-xs uppercase text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                Reset
                            </a>
                        </div>

                    </form>

                    <p class="mb-3 text-sm text-gray-600 dark:text-gray-400">
                        {{ $resultCount }} {{ \Illuminate\Support\Str::plural('result', $resultCount) }} found.
                    </p>

                    {{-- Table --}}
                    <div class="overflow-x-auto relative shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">

                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

                            <thead class="text-xs text-gray-700 uppercase bg-violet-50 dark:bg-violet-900/20 dark:text-gray-300">

                                <tr>
                                    <th class="py-3 px-6">Title</th>
                                    <th class="py-3 px-6">Citizen</th>
                                    <th class="py-3 px-6">Barangay</th>
                                    <th class="py-3 px-6">Category</th>
                                    <th class="py-3 px-6">Location</th>
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

                                        <td class="py-4 px-6">{{ $issue->citizen_name }}</td>
                                        <td class="py-4 px-6">{{ $issue->barangay_name }}</td>
                                        <td class="py-4 px-6">{{ $issue->category_name }}</td>
                                        <td class="py-4 px-6">{{ $issue->specific_location ?? 'N/A' }}</td>
                                        <td class="py-4 px-6">{{ \Illuminate\Support\Str::headline($issue->status) }}</td>
                                        <td class="py-4 px-6">{{ $issue->created_at }}</td>

                                        <td class="py-4 px-6 text-center">
                                            <a href="{{ route('staff.issues.edit', $issue->id) }}"
                                               class="text-sm font-medium text-violet-600 dark:text-violet-400 hover:underline">
                                                Update
                                            </a>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-8 px-6 text-center text-gray-400 italic">
                                            No assigned issues found.
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
</x-app-layout>