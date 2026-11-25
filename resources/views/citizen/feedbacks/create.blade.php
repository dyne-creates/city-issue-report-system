<x-app-layout>
    @section('title', 'Citizen')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rate This Issue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">

                {{-- purple top accent --}}
                <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Report #{{ $issue->id }}
                        </p>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $issue->title }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Your issue has been marked as completed. Let us know how the resolution went.
                        </p>
                    </div>

                    <form action="{{ route('citizen.feedbacks.store', $issue->id) }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Rating --}}
                        <div>
                            <x-input-label :value="__('How satisfied are you with the resolution?')" />

                            <div class="mt-3 flex items-center gap-3" role="radiogroup" aria-label="Satisfaction rating">
                                @foreach ([1, 2, 3, 4, 5] as $value)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $value }}"
                                            class="peer sr-only"
                                            {{ old('rating') == $value ? 'checked' : '' }}
                                            required>
                                        <span
                                            class="flex h-12 w-12 items-center justify-center rounded-full border border-gray-300 dark:border-gray-600 text-lg font-semibold text-gray-600 dark:text-gray-300 transition peer-checked:bg-gradient-to-r peer-checked:from-violet-600 peer-checked:to-purple-600 peer-checked:border-transparent peer-checked:text-white hover:border-violet-400 dark:hover:border-violet-500">
                                            {{ $value }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                            <div class="mt-2 flex justify-between text-xs text-gray-500 dark:text-gray-400 max-w-xs">
                                <span>Poor</span>
                                <span>Excellent</span>
                            </div>

                            <x-input-error class="mt-2" :messages="$errors->get('rating')" />
                        </div>

                        {{-- Comment --}}
                        <div>
                            <x-input-label for="comment" :value="__('Additional Comments (optional)')" />
                            <textarea id="comment" name="comment" rows="4"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600 rounded-md shadow-sm"
                                placeholder="Tell us more about how the issue was handled...">{{ old('comment') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('citizen.issues.show', $issue->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-violet-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-700 focus:bg-violet-700 active:bg-violet-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Submit Feedback') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>