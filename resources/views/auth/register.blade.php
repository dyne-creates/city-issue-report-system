<x-guest-layout>
    @section('title', 'Create Account')

    {{-- Page heading --}}
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">Create your account</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Join KailianFix and start reporting city issues</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Full Name
            </label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Juan dela Cruz"
                class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm transition focus:border-violet-500 focus:ring-2 focus:ring-violet-500/30 focus:outline-none @error('name') border-red-500 focus:border-red-500 focus:ring-red-500/30 @enderror"
            />
            @error('name')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600 dark:text-red-400">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Email Address
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                placeholder="you@example.com"
                class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm transition focus:border-violet-500 focus:ring-2 focus:ring-violet-500/30 focus:outline-none @error('email') border-red-500 focus:border-red-500 focus:ring-red-500/30 @enderror"
            />
            @error('email')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600 dark:text-red-400">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Contact Number --}}
        <div>
            <label for="contact_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Contact Number
            </label>
            <input
                id="contact_number"
                type="text"
                name="contact_number"
                value="{{ old('contact_number') }}"
                required
                autocomplete="tel"
                placeholder="09XXXXXXXXX"
                maxlength="11"
                inputmode="numeric"
                class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm transition focus:border-violet-500 focus:ring-2 focus:ring-violet-500/30 focus:outline-none @error('contact_number') border-red-500 focus:border-red-500 focus:ring-red-500/30 @enderror"
            />
            @error('contact_number')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600 dark:text-red-400">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Barangay --}}
        <div>
            <label for="barangay_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Barangay
            </label>
            <select
                id="barangay_id"
                name="barangay_id"
                required
                class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm text-gray-900 dark:text-gray-100 shadow-sm transition focus:border-violet-500 focus:ring-2 focus:ring-violet-500/30 focus:outline-none @error('barangay_id') border-red-500 focus:border-red-500 focus:ring-red-500/30 @enderror"
            >
                <option value="">— Select your barangay —</option>
                @foreach ($barangays as $barangay)
                    <option value="{{ $barangay->id }}"
                        {{ old('barangay_id') == $barangay->id ? 'selected' : '' }}>
                        {{ $barangay->name }}
                    </option>
                @endforeach
            </select>
            @error('barangay_id')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600 dark:text-red-400">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Password
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Min. 8 characters"
                class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm transition focus:border-violet-500 focus:ring-2 focus:ring-violet-500/30 focus:outline-none @error('password') border-red-500 focus:border-red-500 focus:ring-red-500/30 @enderror"
            />
            @error('password')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600 dark:text-red-400">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Confirm Password
            </label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Repeat your password"
                class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm transition focus:border-violet-500 focus:ring-2 focus:ring-violet-500/30 focus:outline-none @error('password_confirmation') border-red-500 focus:border-red-500 focus:ring-red-500/30 @enderror"
            />
            @error('password_confirmation')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600 dark:text-red-400">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="pt-2">
            <button
                type="submit"
                class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 px-4 py-3 text-sm font-semibold text-white shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-all duration-200 active:scale-[0.98]"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Create Account
            </button>
        </div>
    </form>

    {{-- Divider --}}
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-white dark:bg-gray-800 px-3 text-gray-400 dark:text-gray-500 font-medium tracking-wider">
                Already have an account?
            </span>
        </div>
    </div>

    {{-- Sign in link --}}
    <a
        href="{{ route('login') }}"
        class="flex w-full items-center justify-center gap-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-violet-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-all duration-200"
    >
        Sign in instead
    </a>

    {{-- Back to home --}}
    <p class="mt-6 text-center text-xs text-gray-400 dark:text-gray-500">
        <a href="{{ url('/') }}" class="hover:text-violet-500 transition">← Back to Home</a>
    </p>

</x-guest-layout>
