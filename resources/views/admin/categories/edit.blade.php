<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">

                {{-- purple top accent --}}
                <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Form Header Section -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Edit Category') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Modify details for the selected category record.') }}
                        </p>
                    </div>

                    <!-- Modification Form -->
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                        class="space-y-6 max-w-xl">
                        @csrf
                        @method('PUT') {{-- Required method spoofing for Laravel update resources --}}

                        <input type="hidden" name="page" value="{{ request('page') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">

                        <!-- Category Name Field Input Group -->
                        <div>
                            <x-input-label for="name" :value="__('Category Name')" />
                            <!-- Value safely handles objects/arrays and falls back to old inputs on validation failure -->
                            <x-text-input id="name" name="name" type="text" :value="old('name', is_array($category) ? $category['name'] : $category->name)"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600 rounded-md shadow-sm"
                                required autofocus autocomplete="off" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Category Description Field Input Group -->
                        <div>
                            <x-input-label for="description" :value="__('Category Description')" />
                            <!-- Old session string falls back to database record cleanly inside textarea wrapper -->
                            <textarea id="description" name="description" rows="4"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600 rounded-md shadow-sm">{{ old('description', is_array($category) ? $category['description'] : $category->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Select Department Input Group -->
                        <div>
                            <!-- Label Component matching your existing forms -->
                            <x-input-label for="department_id" :value="__('Department')" />

                            <!-- Parent select element explicitly locking light and dark mode colors -->
                            <select id="department_id" name="department_id"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600 rounded-md shadow-sm"
                                required>

                                <option value="" disabled
                                    class="bg-white dark:bg-gray-900 text-gray-400 dark:text-gray-500">
                                    {{ __('Select Department') }}
                                </option>

                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $category->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Error container matching standard layout validations -->
                            <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
                        </div>


                        <!-- Actions Button Toolbar -->
                        <div
                            class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <!-- Cancel Button Link -->
                            <a href="{{ route('admin.categories.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>

                            <!-- Form Submit Save Button -->
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-violet-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-700 focus:bg-violet-700 active:bg-violet-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Update Category') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>