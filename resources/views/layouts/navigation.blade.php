@php
    $dashboardRoute = match (auth()->user()->role) {
        'admin' => route('admin.dashboard'),
        'staff' => route('staff.dashboard'),
        default => route('citizen.dashboard'),
    };

    // Simple data-driven nav so the sidebar and mobile drawer never drift out of sync
    $navLinks = match (auth()->user()->role) {
        'citizen' => [
            ['route' => 'citizen.issues.index', 'active' => 'citizen.issues.index', 'label' => 'My Reports', 'icon' => 'clipboard'],
            ['route' => 'citizen.issues.create', 'active' => 'citizen.issues.create', 'label' => 'Report Issue', 'icon' => 'plus'],
        ],
        'staff' => [
            ['route' => 'staff.issues.index', 'active' => 'staff.issues.*', 'label' => 'Assigned Issues', 'icon' => 'clipboard'],
        ],
        'admin' => [
            ['route' => 'admin.barangays.index', 'active' => 'admin.barangays.*', 'label' => 'Barangays', 'icon' => 'map'],
            ['route' => 'admin.categories.index', 'active' => 'admin.categories.*', 'label' => 'Categories', 'icon' => 'tag'],
            ['route' => 'admin.departments.index', 'active' => 'admin.departments.*', 'label' => 'Departments', 'icon' => 'building'],
            ['route' => 'admin.issues.index', 'active' => 'admin.issues.*', 'label' => 'Issues', 'icon' => 'clipboard'],
            ['route' => 'admin.status-logs.index', 'active' => 'admin.status-logs.*', 'label' => 'Status Logs', 'icon' => 'history'],
            ['route' => 'admin.users.index', 'active' => 'admin.users.*', 'label' => 'Users', 'icon' => 'users'],
            ['route' => 'admin.reports.index', 'active' => 'admin.reports.*', 'label' => 'Reports', 'icon' => 'chart'],
        ],
        default => [],
    };
@endphp

{{-- Icon partial, inlined so the sidebar has no extra dependency --}}
@php
    $icon = function (string $name, string $class = 'h-5 w-5') {
        $paths = [
            'home'      => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'clipboard' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            'plus'      => 'M12 4v16m8-8H4',
            'map'       => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
            'tag'       => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
            'building'  => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m2-14h14M5 11h14M9 3v18m6-18v18',
            'history'   => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'users'     => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4',
            'chart'     => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        ];
        return $paths[$name] ?? $paths['clipboard'];
    };
@endphp

{{-- Desktop sidebar --}}
<aside class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:w-72 lg:border-r-2 lg:border-purple-400 dark:lg:border-gray-700 lg:bg-white dark:lg:bg-gray-800">

    {{-- Logo --}}
    <a href="{{ $dashboardRoute }}" class="flex items-center gap-3 h-16 px-6 border-b border-slate-100 dark:border-gray-700 shrink-0">
        <img src="{{ asset('logo.png') }}" class="h-10 w-10 rounded-xl" alt="Logo">
        <span class="text-xl font-bold text-slate-800 dark:text-gray-100">KailianFix</span>
    </a>

    {{-- Nav links --}}
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">

        <a href="{{ $dashboardRoute }}"
            @class([
                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                'bg-gradient-to-r from-violet-600 to-purple-600 text-white shadow' => request()->routeIs('admin.dashboard', 'staff.dashboard', 'citizen.dashboard'),
                'text-slate-600 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-gray-700' => !request()->routeIs('admin.dashboard', 'staff.dashboard', 'citizen.dashboard'),
            ])>
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon('home') }}" />
            </svg>
            Dashboard
        </a>

        @if (count($navLinks))
            <div class="pt-4 mt-4 border-t border-slate-100 dark:border-gray-700">
                <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-gray-500">
                    {{ ucfirst(auth()->user()->role) }} Menu
                </p>

                @foreach ($navLinks as $link)
                    <a href="{{ route($link['route']) }}"
                        @class([
                            'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                            'bg-gradient-to-r from-violet-600 to-purple-600 text-white shadow' => request()->routeIs($link['active']),
                            'text-slate-600 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-gray-700' => !request()->routeIs($link['active']),
                        ])>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon($link['icon']) }}" />
                        </svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        @endif
    </nav>

    {{-- User block --}}
    <div class="border-t border-slate-100 dark:border-gray-700 p-4 shrink-0">
        <x-dropdown align="right" width="56">
            <x-slot name="trigger">
                <button class="flex w-full items-center gap-3 rounded-xl px-2 py-2 transition hover:bg-slate-100 dark:hover:bg-gray-700">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-r from-indigo-600 to-violet-600 font-semibold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 text-left">
                        <p class="truncate text-sm font-medium text-slate-700 dark:text-gray-200">{{ Auth::user()->name }}</p>
                        <p class="truncate text-xs text-slate-400 dark:text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        Logout
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</aside>

{{-- Mobile drawer --}}
<div x-show="sidebarOpen" x-cloak class="lg:hidden fixed inset-0 z-40" style="display:none;">
    {{-- backdrop --}}
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/40"></div>

    {{-- panel --}}
    <div x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="relative flex flex-col w-72 max-w-[85%] h-full bg-white dark:bg-gray-800 border-r-2 border-purple-600 dark:border-gray-700">

        <div class="flex items-center justify-between h-16 px-6 border-b border-slate-100 dark:border-gray-700 shrink-0">
            <a href="{{ $dashboardRoute }}" class="flex items-center gap-3" @click="sidebarOpen = false">
                <img src="{{ asset('logo.png') }}" class="h-9 w-9 rounded-xl" alt="Logo">
                <span class="text-lg font-bold text-slate-800 dark:text-gray-100">KailianFix</span>
            </a>
            <button @click="sidebarOpen = false" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 dark:hover:bg-gray-700">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
            <a href="{{ $dashboardRoute }}" @click="sidebarOpen = false"
                @class([
                    'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                    'bg-gradient-to-r from-violet-600 to-purple-600 text-white shadow' => request()->routeIs('admin.dashboard', 'staff.dashboard', 'citizen.dashboard'),
                    'text-slate-600 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-gray-700' => !request()->routeIs('admin.dashboard', 'staff.dashboard', 'citizen.dashboard'),
                ])>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon('home') }}" />
                </svg>
                Dashboard
            </a>

            @if (count($navLinks))
                <div class="pt-4 mt-4 border-t border-slate-100 dark:border-gray-700">
                    <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-gray-500">
                        {{ ucfirst(auth()->user()->role) }} Menu
                    </p>

                    @foreach ($navLinks as $link)
                        <a href="{{ route($link['route']) }}" @click="sidebarOpen = false"
                            @class([
                                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                                'bg-gradient-to-r from-violet-600 to-purple-600 text-white shadow' => request()->routeIs($link['active']),
                                'text-slate-600 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-gray-700' => !request()->routeIs($link['active']),
                            ])>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon($link['icon']) }}" />
                            </svg>
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        </nav>

        <div class="border-t border-slate-100 dark:border-gray-700 p-4 shrink-0">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-r from-indigo-600 to-violet-600 font-semibold text-white">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-slate-700 dark:text-gray-200">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-slate-400 dark:text-gray-500">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" @click="sidebarOpen = false"
                class="mt-2 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-gray-700">
                Profile
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-gray-700">
                    Logout
                </a>
            </form>
        </div>
    </div>
</div>