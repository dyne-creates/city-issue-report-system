<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Staff Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats --}}
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">

                @foreach ($counts as $label => $count)
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-violet-500">

                        <div class="p-5">

                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ \Illuminate\Support\Str::headline($label) }}
                            </p>

                            <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $count }}
                            </p>

                        </div>

                    </div>
                @endforeach

            </div>

            {{-- Recent Issues --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-violet-500">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Recent Department Issues') }}
                            </h3>

                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Reports assigned to your department based on category
                            </p>
                        </div>

                        <a href="{{ route('staff.issues.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-violet-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-700 transition">
                            Manage Issues
                        </a>

                    </div>

                    {{-- Table --}}
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
                                    <th class="py-3 px-6">Status</th>
                                    <th class="py-3 px-6 text-center">Action</th>
                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                @forelse ($recentIssues as $issue)

                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">

                                        <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                            {{ $issue->title }}
                                        </td>

                                        <td class="py-4 px-6">
                                            {{ $issue->citizen_name }}
                                        </td>

                                        <td class="py-4 px-6">
                                            {{ $issue->barangay_name }}
                                        </td>

                                        <td class="py-4 px-6">
                                            {{ $issue->category_name }}
                                        </td>

                                        <td class="py-4 px-6">
                                            {{ \Illuminate\Support\Str::headline($issue->status) }}
                                        </td>

                                        <td class="py-4 px-6 text-center">
                                            <a href="{{ route('staff.issues.edit', $issue->id) }}"
                                                class="text-sm font-medium text-violet-600 dark:text-violet-400 hover:underline">
                                                Update
                                            </a>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="6" class="py-8 px-6 text-center text-gray-400 italic">
                                            No assigned reports yet.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>