<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Issues') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">

                {{-- purple top accent --}}
                <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">{{ __('Manage Issue Status') }}</h3>
                    </div>

                    <form method="GET" action="{{ route('admin.issues.index') }}"
                        class="mb-6 grid gap-4 md:grid-cols-5">
                        <div>
                            <x-input-label for="search" :value="__('Keyword')" />
                            <x-text-input id="search" name="search" type="text" :value="request('search')"
                                class="mt-1 block w-full focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600"
                                placeholder="Title, citizen, location" />
                        </div>
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600">
                                <option value="">All Statuses</option>
                                @foreach (['reported', 'verified', 'in_progress', 'completed'] as $status)
                                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                        {{ \Illuminate\Support\Str::headline($status) }}
                                    </option>
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
                            <a href="{{ route('admin.issues.index') }}"
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
                                    <th class="py-3 px-6">Title</th>
                                    <th class="py-3 px-6">Citizen</th>
                                    <th class="py-3 px-6">Barangay</th>
                                    <th class="py-3 px-6">Category</th>
                                    <th class="py-3 px-6">Department</th>
                                    <th class="py-3 px-6">Status</th>
                                    <th class="py-3 px-6">Reported</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($issues as $issue)
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-violet-50/40 dark:hover:bg-gray-700/50">
                                        <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">{{ $issue->title }}
                                        </td>
                                        <td class="py-4 px-6">{{ $issue->citizen_name }}</td>
                                        <td class="py-4 px-6">{{ $issue->barangay_name }}</td>
                                        <td class="py-4 px-6">{{ $issue->category_name }}</td>
                                        <td class="py-4 px-6">{{ $issue->department_name }}</td>
                                        <td class="py-4 px-6">{{ \Illuminate\Support\Str::headline($issue->status) }}</td>
                                        <td class="py-4 px-6">{{ $issue->created_at }}</td>
                                        <td class="py-4 px-6 text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <a href="{{ route('admin.issues.edit', $issue->id) }}"
                                                    class="text-sm font-medium text-amber-600 dark:text-amber-400 hover:underline">
                                                    Status
                                                </a>
                                                <form action="{{ route('admin.issues.destroy', $issue->id) }}" method="POST"
                                                    x-data="{ open: false }" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    @if($issue->status === 'reported')

                                                        <button type="button" @click="open = true"
                                                            class="text-sm font-medium text-red-600 hover:text-red-700 hover:underline">
                                                            Delete
                                                        </button>
                                                        {{-- Alert Pop up --}}

                                                        <div x-show="open" x-transition
                                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
                                                            style="display: none;">

                                                            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

                                                                {{-- Title --}}
                                                                <h2 class="text-lg font-semibold text-slate-800">
                                                                    Delete Issue {{ $issue->title }}
                                                                </h2>

                                                                {{-- Message --}}
                                                                <p class="mt-2 text-sm text-slate-600">
                                                                    Are you sure you want to delete this issue?
                                                                    This action cannot be undone.
                                                                </p>

                                                                {{-- Actions --}}
                                                                <div class="mt-6 flex justify-end gap-3">

                                                                    <button type="button" @click="open = false"
                                                                        class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                                                                        Cancel
                                                                    </button>

                                                                    <button type="submit"
                                                                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                                                        Delete
                                                                    </button>

                                                                </div>

                                                            </div>
                                                        </div>

                                                    @else

                                                        <span class="text-xs text-slate-500 italic">
                                                            Cannot delete once work has begun.
                                                        </span>

                                                    @endif
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-8 px-6 text-center text-gray-400 italic">No issues found.
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