@props(['href'])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'flex w-full items-center rounded-xl px-4 py-3 text-sm font-medium text-slate-700 transition-all duration-200 hover:bg-slate-100 hover:text-indigo-600'
    ]) }}
>
    {{ $slot }}
</a>