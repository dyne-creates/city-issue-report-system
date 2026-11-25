<x-app-layout>
    @section('title', 'Status Log Details')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Status Log Details') }} #{{ $statusLog->id }}
            </h2>
            <a href="{{ route('admin.status-logs.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-xs uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                &larr; Back to Logs
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">
                <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>

                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-violet-600 dark:text-violet-400">
                            Audit Record #{{ $statusLog->id }}
                        </span>
                        <h3 class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">
                            {{ $statusLog->issue_title }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Associated with Report #{{ $statusLog->issue_id }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="rounded-xl bg-slate-50 dark:bg-gray-900/50 p-4 border border-slate-200/60 dark:border-gray-700">
                            <span class="text-xs uppercase font-medium text-gray-500 dark:text-gray-400">Status Transition</span>
                            <div class="mt-2 flex items-center gap-2 text-base font-semibold">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $statusLog->old_status ? \Illuminate\Support\Str::headline($statusLog->old_status) : 'Initial Submission' }}
                                </span>
                                <span class="text-gray-400">&rarr;</span>
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-900/40 dark:text-violet-300">
                                    {{ \Illuminate\Support\Str::headline($statusLog->new_status) }}
                                </span>
                            </div>
                        </div>

                        <div class="rounded-xl bg-slate-50 dark:bg-gray-900/50 p-4 border border-slate-200/60 dark:border-gray-700">
                            <span class="text-xs uppercase font-medium text-gray-500 dark:text-gray-400">Recorded By</span>
                            <p class="mt-2 text-base font-semibold text-gray-900 dark:text-white">
                                {{ $statusLog->changed_by_name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ \Carbon\Carbon::parse($statusLog->created_at)->format('F d, Y • h:i A') }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                            Remarks & Action Taken
                        </h4>
                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-slate-50 dark:bg-gray-900/50 p-4 rounded-xl border border-slate-200/60 dark:border-gray-700 whitespace-pre-line">
                            {{ $statusLog->remarks ?: 'No remarks provided.' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('admin.status-logs.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Back to All Logs
                        </a>

                        <a href="{{ route('admin.issues.edit', $statusLog->issue_id) }}"
                            class="inline-flex items-center px-4 py-2 bg-violet-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-700 transition">
                            View Issue Report &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
