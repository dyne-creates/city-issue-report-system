<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Issue Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <div
                    class="relative lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">

                    <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium">{{ $issue->title }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Submitted by {{ $issue->citizen_name }} in {{ $issue->barangay_name }}.
                            </p>
                        </div>

                        <dl class="grid gap-4 sm:grid-cols-2 mb-6 text-sm">
                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">Category</dt>
                                <dd>{{ $issue->category_name }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">Department</dt>
                                <dd>{{ $issue->department_name }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">Specific Location</dt>
                                <dd>{{ $issue->specific_location ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">Current Status</dt>
                                <dd>{{ \Illuminate\Support\Str::headline($issue->status) }}</dd>
                            </div>
                        </dl>

                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-200">Citizen Description</h4>
                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                {{ $issue->description }}
                            </p>
                        </div>

                        @if ($issue->photo_path)
                            <h4 class="font-semibold text-gray-700 dark:text-gray-200">Photo Evidence</h4>
                            <div class="mb-6">
                                <img src="{{ asset('storage/' . $issue->photo_path) }}" alt="Issue Photo"
                                    class="max-h-96 rounded-md border border-gray-200 dark:border-gray-700">
                            </div>
                        @endif

                        <form action="{{ route('admin.issues.update', $issue->id) }}" method="POST" class="space-y-6">

                            @csrf
                            @method('PUT')

                            <input type="hidden" name="page" value="{{ request('page') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">

                            <div>
                                <x-input-label for="status" :value="__('New Status')" />
                                <select id="status" name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600"
                                    required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ old('status', $issue->status) === $status ? 'selected' : '' }}>
                                            {{ \Illuminate\Support\Str::headline($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div>
                                <x-input-label for="remarks" :value="__('Remarks')" />
                                <textarea id="remarks" name="remarks" rows="4"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600">{{ old('remarks') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('remarks')" />
                            </div>

                            <div
                                class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <a href="{{ route('admin.issues.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">Cancel</a>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-violet-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-700 focus:bg-violet-700 active:bg-violet-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Update Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- Status History --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <h3 class="text-lg font-medium mb-4">Status History</h3>

                        <div class="space-y-4">

                            @forelse ($statusLogs as $log)
                                <div class="border-l-4 border-violet-500 pl-4">

                                    <p class="text-sm font-semibold">
                                        {{ \Illuminate\Support\Str::headline($log->old_status ?? 'initial') }}
                                        to
                                        {{ \Illuminate\Support\Str::headline($log->new_status) }}
                                    </p>

                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $log->changed_by_display }} • {{ \Carbon\Carbon::parse($log->created_at)->format('F d, Y h:i A') }}
                                    </p>

                                    @if ($log->remarks)
                                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $log->remarks }}
                                        </p>
                                    @endif

                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No status history yet.</p>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>