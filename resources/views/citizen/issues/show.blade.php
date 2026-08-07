<x-app-layout>
    @section('title', 'Citizen')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Issue Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid gap-6 lg:grid-cols-3">

                {{-- MAIN DETAILS --}}
                <div
                    class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-violet-500">

                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        {{-- HEADER --}}
                        <div class="mb-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Report #{{ $issue->id }}
                            </p>

                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $issue->title }}
                            </h3>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ $issue->barangay_name }} -
                                {{ $issue->specific_location ?? 'No specific location provided' }}
                            </p>
                        </div>

                        {{-- DETAILS GRID --}}
                        <dl class="grid gap-4 sm:grid-cols-2 mb-6 text-sm">

                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">Category</dt>
                                <dd class="text-gray-900 dark:text-gray-100">{{ $issue->category_name }}</dd>
                            </div>

                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">Department</dt>
                                <dd class="text-gray-900 dark:text-gray-100">{{ $issue->department_name }}</dd>
                            </div>

                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="text-gray-900 dark:text-gray-100">
                                    {{ \Illuminate\Support\Str::headline($issue->status) }}
                                </dd>
                            </div>

                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">Submitted</dt>
                                <dd class="text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($issue->created_at)->format('F d, Y h:i A') }}
                                </dd>
                            </div>

                            @if ($issue->resolved_at)
                                <div>
                                    <dt class="font-semibold text-gray-500 dark:text-gray-400">Resolved At</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($issue->resolved_at)->format('F d, Y h:i A') }}
                                    </dd>
                                </div>
                            @endif

                        </dl>

                        {{-- DESCRIPTION --}}
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-200">
                                Description
                            </h4>

                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                {{ $issue->description }}
                            </p>
                        </div>

                        {{-- IMAGE --}}
                        @if ($issue->photo_path)
                            <div class="mb-6">
                                <img src="{{ asset('storage/' . $issue->photo_path) }}" alt="Issue Photo"
                                    class="max-h-96 w-full object-cover rounded-md border border-violet-200 dark:border-gray-700 shadow-sm">
                            </div>
                        @endif

                        {{-- BACK BUTTON --}}
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('citizen.issues.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-violet-300 text-violet-700 rounded-md text-xs uppercase hover:bg-violet-50">
                                Back to Reports
                            </a>
                        </div>

                    </div>
                </div>

                {{-- STATUS HISTORY --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-violet-500">

                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">
                            Status History
                        </h3>

                        <div class="space-y-4">

                            @forelse ($statusLogs as $log)
                                <div class="border-l-4 border-violet-500 pl-4">

                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ \Illuminate\Support\Str::headline($log->old_status ?? 'initial') }}
                                        →
                                        {{ \Illuminate\Support\Str::headline($log->new_status) }}
                                    </p>

                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $log->changed_by_display }} • {{ $log->formatted_date }}
                                    </p>

                                    @if ($log->remarks)
                                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $log->remarks }}
                                        </p>
                                    @endif

                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    No status history yet.
                                </p>
                            @endforelse

                        </div>

                    </div>
                </div>

            </div>

            {{-- FEEDBACK --}}
            @if ($issue->status === 'completed')
                <div
                    class="mt-6 relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">

                    <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        @if ($feedback)
                            <h3 class="text-lg font-medium mb-3 text-gray-900 dark:text-white">
                                Your Feedback
                            </h3>

                            <div class="flex items-center gap-2 mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                            <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-semibold
                                                                {{ $i <= $feedback->rating
                                    ? 'bg-gradient-to-r from-violet-600 to-purple-600 text-white'
                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500' }}">
                                                {{ $i }}
                                            </span>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $feedback->rating }} / 5
                                </span>
                            </div>

                            @if ($feedback->comment)
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                    {{ $feedback->comment }}
                                </p>
                            @endif

                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Submitted {{ \Carbon\Carbon::parse($feedback->created_at)->format('F d, Y h:i A') }}
                            </p>
                        @else
                            <h3 class="text-lg font-medium mb-2 text-gray-900 dark:text-white">
                                How did we do?
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                This issue has been marked as completed. Let us know how the resolution went.
                            </p>
                            <a href="{{ route('citizen.feedbacks.create', $issue->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-violet-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-700 focus:bg-violet-700 active:bg-violet-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Rate This Issue
                            </a>
                        @endif

                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>