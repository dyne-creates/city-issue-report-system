<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Report City Issue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-violet-500">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-6">
                        <h3 class="text-lg font-medium">{{ __('Submit a New Report') }}</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Give enough detail so the responsible department can verify and process the issue.
                        </p>
                    </div>

                    <form action="{{ route('citizen.issues.store') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="space-y-6 max-w-3xl">

                        @csrf

                        {{-- TITLE --}}
                        <div>
                            <x-input-label for="title" :value="__('Issue Title')" />
                            <x-text-input id="title" name="title" type="text"
                                :value="old('title')"
                                class="mt-1 block w-full"
                                required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        {{-- BARANGAY --}}
                        <div>
                            <x-input-label for="barangay_id" :value="__('Barangay')" />
                            <select id="barangay_id" name="barangay_id"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                required>

                                <option value="" disabled {{ old('barangay_id', auth()->user()->barangay_id) ? '' : 'selected' }}>
                                    Select Barangay
                                </option>

                                @foreach ($barangays as $barangay)
                                    <option value="{{ $barangay->id }}"
                                        {{ old('barangay_id', auth()->user()->barangay_id) == $barangay->id ? 'selected' : '' }}>
                                        {{ $barangay->name }}
                                    </option>
                                @endforeach

                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('barangay_id')" />
                        </div>

                        {{-- CATEGORY --}}
                        <div>
                            <x-input-label for="category_id" :value="__('Issue Category')" />
                            <select id="category_id" name="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                required>

                                <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>
                                    Select Category
                                </option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->department_name }} - {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        {{-- LOCATION --}}
                        <div>
                            <x-input-label for="specific_location" :value="__('Specific Location')" />
                            <x-text-input id="specific_location" name="specific_location" type="text"
                                :value="old('specific_location')"
                                class="mt-1 block w-full"
                                placeholder="Example: Near Session Road, beside the waiting shed" />
                            <x-input-error class="mt-2" :messages="$errors->get('specific_location')" />
                        </div>

                        {{-- DESCRIPTION --}}
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="6"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                required>{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        {{-- PHOTO --}}
                        <div>
                            <x-input-label for="photo" :value="__('Photo Evidence')" />

                            <input id="photo" name="photo" type="file"
                                accept="image/jpeg,image/png,image/webp"
                                class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300" />

                            <x-input-error class="mt-2" :messages="$errors->get('photo')" />

                            <div class="mt-4">
                                <img id="photo-preview"
                                    class="hidden w-64 rounded-lg border border-violet-200 shadow"
                                    alt="Image Preview">
                            </div>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">

                            <a href="{{ route('citizen.issues.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-violet-300 text-violet-700 rounded-md text-xs uppercase hover:bg-violet-50">
                                Cancel
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-violet-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-violet-700">
                                Submit Report
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<script>
document.getElementById('photo').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById('photo-preview');

    if (!file) {
        preview.classList.add('hidden');
        preview.src = "";
        return;
    }

    preview.src = URL.createObjectURL(file);
    preview.classList.remove('hidden');
});
</script>