<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Issue Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid gap-6 lg:grid-cols-3">

                {{-- MAIN --}}
                <div
                    class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-violet-500">

                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <div class="mb-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Report #{{ $issue->id }}</p>
                            <h3 class="text-xl font-semibold">{{ $issue->title }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Submitted by {{ $issue->citizen_name }} in {{ $issue->barangay_name }}
                            </p>
                        </div>

                        <dl class="grid gap-4 sm:grid-cols-2 mb-6 text-sm">

                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">Category</dt>
                                <dd>{{ $issue->category_name }}</dd>
                            </div>

                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">Location</dt>
                                <dd>{{ $issue->specific_location ?? 'N/A' }}</dd>
                            </div>

                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">Status</dt>
                                <dd>{{ \Illuminate\Support\Str::headline($issue->status) }}</dd>
                            </div>

                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">Reported</dt>
                                <dd>{{ \Carbon\Carbon::parse($issue->created_at)->format('F d, Y h:i A') }}</dd>
                            </div>

                        </dl>

                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-200">Description</h4>
                            <p class="mt-2 text-sm whitespace-pre-line">{{ $issue->description }}</p>
                        </div>

                        @if ($issue->photo_path)
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-200">Photo Evidence</h4>
                                <img src="{{ asset('storage/' . $issue->photo_path) }}"
                                    class="max-h-96 rounded-md border border-gray-200 dark:border-gray-700">
                            </div>
                        @endif

                        {{-- FORM --}}
                        <form action="{{ route('staff.issues.update', $issue->id) }}" method="POST" class="space-y-6">

                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label for="status" :value="__('New Status')" />

                                <select id="status" name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required>

                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ old('status', $issue->status) === $status ? 'selected' : '' }}>
                                            {{-- to display the old and new status in a readable format --}}
                                            {{ \Illuminate\Support\Str::headline($status) }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div>
                                <x-input-label for="remarks" :value="__('Remarks')" />
                                <textarea id="remarks" name="remarks" rows="4"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">

                                <a href="{{ route('staff.issues.index') }}"
                                    class="px-4 py-2 border rounded-md text-xs uppercase">
                                    Cancel
                                </a>

                                <button type="submit"
                                    class="px-4 py-2 bg-violet-600 text-white rounded-md text-xs uppercase hover:bg-violet-700 transition">
                                    Update Status
                                </button>

                            </div>

                        </form>

                    </div>
                </div>

                {{-- HISTORY --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-violet-500">

                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Status History</h3>

                        <div class="space-y-4">

                            @forelse ($statusLogs as $log)
                                <div class="border-l-4 border-violet-500 pl-4">

                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ \Illuminate\Support\Str::headline($log->old_status ?? 'initial') }}
                                        →
                                        {{ \Illuminate\Support\Str::headline($log->new_status) }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $log->changed_by_display }} • {{ \Carbon\Carbon::parse($log->created_at)->format('F d, Y h:i A') }}
                                    </p>

                                    @if ($log->remarks)
                                        <p class="text-sm mt-1 text-gray-700 dark:text-gray-300">
                                            {{ $log->remarks }}
                                        </p>
                                    @endif

                                </div>
                            @empty
                                <p class="text-sm text-gray-400">No history yet.</p>
                            @endforelse

                        </div>
                    </div>
                </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>