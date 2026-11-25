<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>KailianFix • @yield('title')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link href="https://fonts.bunny.net/css?family=figtree:400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<script>
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
</script>

<body class="bg-slate-100 dark:bg-gray-900 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen lg:flex">

        @include('layouts.navigation')

        {{-- Main column --}}
        <div class="flex-1 min-w-0 lg:pl-72">

            {{-- Mobile top bar --}}
            <div class="sticky top-0 z-30 flex items-center justify-between h-16 px-4 bg-white/90 dark:bg-gray-800/90 backdrop-blur border-b-2 border-purple-600 dark:border-gray-700 lg:hidden">
                <a href="{{ match (auth()->user()->role) {
                        'admin' => route('admin.dashboard'),
                        'staff' => route('staff.dashboard'),
                        default => route('citizen.dashboard'),
                    } }}" class="flex items-center gap-2">
                    <img src="{{ asset('logo.png') }}" class="h-8 w-8 rounded-lg" alt="Logo">
                    <span class="text-lg font-bold text-slate-800 dark:text-gray-100">KailianFix</span>
                </a>

                <button @click="sidebarOpen = true"
                    class="rounded-xl p-2 text-slate-700 dark:text-gray-200 hover:bg-slate-100 dark:hover:bg-gray-700">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            @isset($header)
                <header
                    class="h-16 flex items-center border-b bg-white dark:bg-gray-800 dark:border-gray-700">
                    <div class="px-6 w-full">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="px-6 py-6">
                {{ $slot }}
            </main>

        </div>

    </div>

</body>

</html>