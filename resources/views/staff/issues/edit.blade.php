<x-app-layout>
    @section('title', 'Staff')

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
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Report #{{ $issue->id }}
                            </p>

                            <h3 class="text-xl font-semibold">
                                {{ $issue->title }}
                            </h3>

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
                            <h4 class="font-semibold text-gray-700 dark:text-gray-200">
                                Description
                            </h4>

                            <p class="mt-2 text-sm whitespace-pre-line">
                                {{ $issue->description }}
                            </p>
                        </div>

                        @if ($issue->photo_path)
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-200">
                                    Photo Evidence
                                </h4>

                                <img src="{{ route('issue.photo', $issue->id) }}"
                                    class="max-h-96 rounded-md border border-gray-200 dark:border-gray-700"
                                    alt="Issue Photo">
                            </div>
                        @endif

                        {{-- UPDATE FORM --}}
                        @if ($issue->status !== 'completed')

                            <form action="{{ route('staff.issues.update', $issue->id) }}" method="POST" id="updateIssueForm" class="space-y-6">

                                @csrf
                                @method('PUT')

                                <div>
                                    <x-input-label for="status" :value="__('New Status')" />

                                    <select id="status" name="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                        required>

                                        {{-- Placeholder --}}
                                        <option value="" disabled selected>
                                            Current: {{ \Illuminate\Support\Str::headline($issue->status) }}
                                        </option>

                                        @foreach($statuses as $status)

                                            {{-- Prevent selecting the current status --}}
                                            @if($status !== $issue->status)

                                                <option value="{{ $status }}">
                                                    {{ \Illuminate\Support\Str::headline($status) }}
                                                </option>

                                            @endif

                                        @endforeach

                                    </select>
                                </div>


                                <div>
                                    <x-input-label for="remarks" :value="__('Remarks')" />

                                    @if ($issue->status === 'in_progress')
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Required when marking the issue as completed.
                                        </p>
                                    @endif

                                    <textarea id="remarks" name="remarks" rows="4"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                        placeholder="Enter remarks about the action taken...">{{ old('remarks') }}</textarea>

                                </div>


                                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">

                                    <a href="{{ route('staff.issues.index') }}"
                                        class="px-4 py-2 border rounded-md text-xs uppercase">
                                        Cancel
                                    </a>

                                    <button type="submit"
                                        class="px-4 py-2 bg-violet-600 text-white rounded-md text-xs uppercase hover:bg-violet-700 transition">
                                        Update
                                    </button>

                                </div>

                            </form>


                            {{-- Validation Script --}}
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {

                                    const form = document.getElementById('updateIssueForm');
                                    const status = document.getElementById('status');
                                    const remarks = document.getElementById('remarks');

                                    form.addEventListener('submit', function (event) {

                                        const selectedStatus = status.value;
                                        const enteredRemarks = remarks.value.trim();


                                        if (selectedStatus === 'completed' && enteredRemarks === '') {

                                            event.preventDefault();

                                            alert(
                                                'Remarks are required when completing an issue.\nPlease describe the action taken before updating the status.'
                                            );

                                            remarks.focus();

                                            return false;
                                        }

                                    });

                                });
                            </script>


                        @else

                            {{-- COMPLETED MESSAGE --}}
                            <div
                                class="mt-6 rounded-lg border border-green-300 bg-green-50 dark:border-green-700 dark:bg-green-900/30 p-4">

                                <p class="font-medium text-green-700 dark:text-green-300">
                                    This issue has already been completed.
                                </p>

                                <p class="mt-1 text-sm text-green-600 dark:text-green-400">
                                    Staff can no longer update the status or remarks of a completed issue.
                                </p>

                                <div class="mt-4">
                                    <a href="{{ route('staff.issues.index') }}"
                                        class="inline-flex px-4 py-2 border rounded-md text-xs uppercase">
                                        Back to Issues
                                    </a>
                                </div>

                            </div>

                        @endif

                    </div>
                </div>

                {{-- HISTORY --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-violet-500">

                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">
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

                                    <p class="text-xs text-gray-500">
                                        {{ $log->changed_by_display }}
                                        •
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('F d, Y h:i A') }}
                                    </p>

                                    @if ($log->remarks)
                                        <p class="text-sm mt-1 text-gray-700 dark:text-gray-300">
                                            {{ $log->remarks }}
                                        </p>
                                    @endif

                                </div>

                            @empty

                                <p class="text-sm text-gray-400">
                                    No history yet.
                                </p>

                            @endforelse

                        </div>
                    </div>

                </div>

            </div>

            {{-- CITIZEN FEEDBACK --}}
            @if ($issue->status === 'completed')

                <div
                    class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-violet-500">

                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <h3 class="text-lg font-medium mb-4">
                            Citizen Feedback
                        </h3>

                        @if ($feedback)

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
                                Submitted
                                {{ \Carbon\Carbon::parse($feedback->created_at)->format('F d, Y h:i A') }}
                            </p>

                        @else

                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                                The citizen has not submitted feedback for this issue yet.
                            </p>

                        @endif

                    </div>

                </div>

            @endif

        </div>
    </div>
</x-app-layout>