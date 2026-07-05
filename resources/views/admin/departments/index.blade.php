<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Departments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-violet-100 dark:border-gray-700">

                {{-- purple top accent --}}
                <div class="h-1 w-full bg-gradient-to-r from-violet-600 to-purple-600"></div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Action Bar Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Manage Departments') }}
                        </h3>
                        <a href="{{ route('admin.departments.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-violet-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-700 focus:bg-violet-700 active:bg-violet-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            + Add New Department
                        </a>
                    </div>

                    <form method="GET" action="{{ route('admin.departments.index') }}"
                        class="mb-6 grid gap-4 md:grid-cols-4">
                        <div>
                            <x-input-label for="search" :value="__('Name or Description')" />
                            <x-text-input id="search" name="search" type="text" :value="request('search')"
                                class="mt-1 block w-full focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600"
                                placeholder="Search department" />
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="px-4 py-2 bg-violet-600 text-white rounded-md text-xs uppercase hover:bg-violet-700 transition">Search</button>
                            <a href="{{ route('admin.departments.index') }}"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-500 rounded-md text-xs uppercase text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Reset</a>
                        </div>
                    </form>

                    <p class="mb-3 text-sm text-gray-600 dark:text-gray-400">
                        {{ $resultCount }} {{ \Illuminate\Support\Str::plural('result', $resultCount) }} found.
                    </p>

                    <!-- Responsive Data Table -->
                    <div
                        class="overflow-x-auto relative shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-violet-50 dark:bg-violet-900/20 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 font-semibold">Department Name</th>
                                    <th scope="col" class="py-3 px-6 font-semibold">Department Description</th>
                                    <th scope="col" class="py-3 px-6 text-center font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($departments as $department)
                                    <tr
                                        class="bg-white dark:bg-gray-800 hover:bg-violet-50/40 dark:hover:bg-gray-700/50 transition-colors">
                                        <!-- Check object syntax or array fallback cleanly -->
                                        <td class="py-4 px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ $department->name }}
                                        </td>
                                        <td class="py-4 px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ $department->description }}
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <!-- Edit Button -->
                                                <a href="{{ route('admin.departments.edit', $department->id) }}"
                                                    class="text-sm font-medium text-amber-600 dark:text-amber-400 hover:underline">
                                                    Edit
                                                </a>

                                                <!-- Delete Button Form -->
                                                <form action="{{ route('admin.departments.destroy', $department->id) }}"
                                                    x-data="{ open: false }" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" @click="open = true"
                                                        class="text-sm font-medium text-red-600 hover:text-red-700 hover:underline">
                                                        Delete
                                                    </button>
                                                    {{-- Alert Pop up --}}

                                                    <div x-show="open" x-transition
                                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
                                                        style="display: none;">

                                                        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

                                                            {{-- Title --}}
                                                            <h2 class="text-lg font-semibold text-slate-800">
                                                                Delete Department {{ $department->name }}
                                                            </h2>

                                                            {{-- Message --}}
                                                            <p class="mt-2 text-sm text-slate-600">
                                                                Are you sure you want to delete this department?
                                                                This action cannot be undone.
                                                            </p>

                                                            {{-- Actions --}}
                                                            <div class="mt-6 flex justify-end gap-3">

                                                                <button type="button" @click="open = false"
                                                                    class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                                                                    Cancel
                                                                </button>

                                                                <button type="submit"
                                                                    class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                                                    Delete
                                                                </button>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Fallback template when rows are missing -->
                                    <tr>
                                        <td colspan="3"
                                            class="py-8 px-6 text-center text-gray-400 dark:text-gray-500 italic">
                                            No departments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $departments->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>