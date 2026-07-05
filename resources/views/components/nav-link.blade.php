@props([
    'active' => false,
])

@php
    $classes = $active
        ? 'inline-flex items-center rounded-full bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition-all duration-200'
        : 'inline-flex items-center rounded-full px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-indigo-600 transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>