<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($counts ?? [] as $label => $count)
                    <div
                        class="relative rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-violet-100 dark:border-gray-700 overflow-hidden">

                        {{-- purple top accent --}}
                        <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>

                        <div class="p-6">
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

            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    Use the admin navigation to manage users, barangays, departments, categories, and issue status updates.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>