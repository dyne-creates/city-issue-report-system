<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>KailianFix • @yield('title')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">

    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<script>
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
</script>

<body
    class="min-h-screen bg-gradient-to-br from-indigo-900 via-indigo-700 to-blue-500 flex items-center justify-center p-6">

    <div class="w-full max-w-md">

        <div class="text-center mb-8">

            <a href="/"
                class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-white shadow-lg mx-auto">

                <img src="{{ asset('logo.png') }}" class="h-12 w-12 object-contain">

            </a>

            <h1 class="text-3xl font-bold text-white mt-4">
                KailianFix
            </h1>

            <p class="text-indigo-100 mt-2">
                City Issue Reporting Platform
            </p>

        </div>

        <div class="rounded-3xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl shadow-2xl p-8">

            {{ $slot }}

        </div>

    </div>

</body>

</html>