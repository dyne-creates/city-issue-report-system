@php
    $dashboardRoute = match (auth()->user()->role) {
        'admin' => route('admin.dashboard'),
        'staff' => route('staff.dashboard'),
        default => route('citizen.dashboard'),
    };
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-200">

    <div class="mx-auto max-w-7xl">

        <div class="flex h-16 items-center justify-between px-6">

            {{-- Logo --}}
            <a href="{{ $dashboardRoute }}" class="flex items-center gap-3">

                <img src="{{ asset('logo.png') }}" class="h-10 w-10 rounded-xl" alt="Logo">

                <span class="text-xl font-bold text-slate-800">
                    KailianFix
                </span>

            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden lg:flex items-center gap-2">

                {{-- Dashboard --}}
                <x-nav-link :href="$dashboardRoute" :active="request()->routeIs('admin.dashboard', 'staff.dashboard', 'citizen.dashboard')">
                    Dashboard
                </x-nav-link>

                {{-- Citizen --}}
                @if(auth()->user()->role == 'citizen')

                    <x-nav-link :href="route('citizen.issues.index')" :active="request()->routeIs('citizen.issues.index')">
                        My Reports
                    </x-nav-link>

                    <x-nav-link :href="route('citizen.issues.create')"
                        :active="request()->routeIs('citizen.issues.create')">
                        Report Issue
                    </x-nav-link>

                @endif

                {{-- Staff --}}
                @if(auth()->user()->role == 'staff')

                    <x-nav-link :href="route('staff.issues.index')" :active="request()->routeIs('staff.issues.*')">
                        Assigned Issues
                    </x-nav-link>

                @endif

                {{-- Admin --}}
                @if(auth()->user()->role == 'admin')

                    <x-nav-link :href="route('admin.barangays.index')" :active="request()->routeIs('admin.barangays.*')">
                        Barangays
                    </x-nav-link>

                    <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        Categories
                    </x-nav-link>

                    <x-nav-link :href="route('admin.departments.index')"
                        :active="request()->routeIs('admin.departments.*')">
                        Departments
                    </x-nav-link>

                    <x-nav-link :href="route('admin.issues.index')" :active="request()->routeIs('admin.issues.*')">
                        Issues
                    </x-nav-link>

                    <x-nav-link :href="route('admin.status-logs.index')"
                        :active="request()->routeIs('admin.status-logs.*')">
                        Status Logs
                    </x-nav-link>

                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        Users
                    </x-nav-link>

                @endif

            </div>

            {{-- User Dropdown --}}
            <div class="hidden lg:flex items-center">

                <x-dropdown align="right" width="56">

                    <x-slot name="trigger">

                        <button
                            class="flex items-center gap-3 rounded-full bg-slate-100 px-2 py-2 transition hover:bg-slate-200">

                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-r from-indigo-600 to-violet-600 font-semibold text-white">

                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                            </div>

                            <span class="font-medium text-slate-700">
                                {{ Auth::user()->name }}
                            </span>

                            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />

                            </svg>

                        </button>

                    </x-slot>

                    <x-slot name="content">

                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">

                                Logout

                            </x-dropdown-link>

                        </form>

                    </x-slot>

                </x-dropdown>

            </div>

            {{-- Mobile Button --}}
            <button @click="open=!open" class="rounded-xl p-2 hover:bg-slate-100 lg:hidden">

                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />

                </svg>

            </button>

        </div>

        {{-- Mobile Navigation --}}
        <div x-show="open" x-transition class="border-t border-slate-200 lg:hidden">

            <div class="space-y-2 p-4">

                <div class="pb-3 border-b">

                    <div class="font-semibold">
                        {{ Auth::user()->name }}
                    </div>

                    <div class="text-sm text-slate-500">
                        {{ Auth::user()->email }}
                    </div>

                </div>

                <x-responsive-nav-link :href="$dashboardRoute" :active="request()->routeIs('admin.dashboard', 'staff.dashboard', 'citizen.dashboard')">

                    Dashboard

                </x-responsive-nav-link>

                @if(auth()->user()->role == 'citizen')

                    <x-responsive-nav-link :href="route('citizen.issues.index')"
                        :active="request()->routeIs('citizen.issues.index')">

                        My Reports

                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('citizen.issues.create')"
                        :active="request()->routeIs('citizen.issues.create')">

                        Report Issue

                    </x-responsive-nav-link>

                @endif

                @if(auth()->user()->role == 'staff')

                    <x-responsive-nav-link :href="route('staff.issues.index')"
                        :active="request()->routeIs('staff.issues.*')">

                        Assigned Issues

                    </x-responsive-nav-link>

                @endif

                @if(auth()->user()->role == 'admin')

                    <x-responsive-nav-link :href="route('admin.barangays.index')"
                        :active="request()->routeIs('admin.barangays.*')">

                        Barangays

                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.categories.index')"
                        :active="request()->routeIs('admin.categories.*')">

                        Categories

                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.departments.index')"
                        :active="request()->routeIs('admin.departments.*')">

                        Departments

                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.issues.index')"
                        :active="request()->routeIs('admin.issues.*')">

                        Issues

                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.status-logs.index')"
                        :active="request()->routeIs('admin.status-logs.*')">

                        Status Logs

                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">

                        Users

                    </x-responsive-nav-link>

                @endif

                <div class="border-t pt-2">

                    <x-responsive-nav-link :href="route('profile.edit')">

                        Profile

                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">

                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">

                            Logout

                        </x-responsive-nav-link>

                    </form>

                </div>

            </div>

        </div>

    </div>

</nav>