<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Status Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">

                {{-- purple top accent --}}
                <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('error'))
                        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-lg font-medium">{{ __('Issue Status Audit Trail') }}</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Status logs are created automatically whenever an issue status changes.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('admin.status-logs.index') }}"
                        class="mb-6 grid gap-4 md:grid-cols-5">
                        <div>
                            <x-input-label for="search" :value="__('Keyword')" />
                            <x-text-input id="search" name="search" type="text" :value="request('search')"
                                class="mt-1 block w-full focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600"
                                placeholder="Issue, user, remarks" />
                        </div>
                        <div>
                            <x-input-label for="new_status" :value="__('New Status')" />
                            <select id="new_status" name="new_status"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600">
                                <option value="">All Statuses</option>
                                @foreach (['reported', 'verified', 'in_progress', 'completed'] as $status)
                                    <option value="{{ $status }}" {{ request('new_status') === $status ? 'selected' : '' }}>
                                        {{ \Illuminate\Support\Str::headline($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="date_from" :value="__('From')" />
                            <x-text-input id="date_from" name="date_from" type="date" :value="request('date_from')"
                                class="mt-1 block w-full focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600" />
                        </div>
                        <div>
                            <x-input-label for="date_to" :value="__('To')" />
                            <x-text-input id="date_to" name="date_to" type="date" :value="request('date_to')"
                                class="mt-1 block w-full focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600" />
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="px-4 py-2 bg-violet-600 text-white rounded-md text-xs uppercase hover:bg-violet-700 transition">Search</button>
                            <a href="{{ route('admin.status-logs.index') }}"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-500 rounded-md text-xs uppercase text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Reset</a>
                        </div>
                    </form>

                    <p class="mb-3 text-sm text-gray-600 dark:text-gray-400">
                        {{ $resultCount }} {{ \Illuminate\Support\Str::plural('result', $resultCount) }} found.
                    </p>

                    <div
                        class="overflow-x-auto relative shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-violet-50 dark:bg-violet-900/20 dark:text-gray-300">
                                <tr>
                                    <th class="py-3 px-6">Issue</th>
                                    <th class="py-3 px-6">Old Status</th>
                                    <th class="py-3 px-6">New Status</th>
                                    <th class="py-3 px-6">Changed By</th>
                                    <th class="py-3 px-6">Remarks</th>
                                    <th class="py-3 px-6">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($statusLogs as $statusLog)
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-violet-50/40 dark:hover:bg-gray-700/50">
                                        <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                            #{{ $statusLog->issue_id }} - {{ $statusLog->issue_title }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $statusLog->old_status ? \Illuminate\Support\Str::headline($statusLog->old_status) : 'Initial' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ \Illuminate\Support\Str::headline($statusLog->new_status) }}</td>
                                        <td class="py-4 px-6">{{ $statusLog->changed_by_display }}</td>
                                        <td class="py-4 px-6">{{ $statusLog->remarks ?? 'N/A' }}</td>
                                        <td class="py-4 px-6">{{ \Carbon\Carbon::parse($statusLog->created_at)->format('F d, Y h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 px-6 text-center text-gray-400 italic">No status logs
                                            found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $statusLogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>