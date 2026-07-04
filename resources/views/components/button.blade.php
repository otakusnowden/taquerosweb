@props([
    'variant' => 'primary', // primary | secondary | ghost | whatsapp | white
    'size' => 'md',         // sm | md | lg
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-semibold rounded-xl '
        . 'transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 '
        . 'focus-visible:ring-offset-2 disabled:opacity-60 disabled:pointer-events-none whitespace-nowrap';

    $sizes = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-5 py-3 text-[0.95rem]',
        'lg' => 'px-7 py-4 text-base',
    ];

    $variants = [
        'primary'  => 'bg-cta-600 text-white shadow-cta hover:bg-cta-700 hover:-translate-y-0.5 focus-visible:ring-cta-600',
        'secondary'=> 'bg-brand-600 text-white shadow-card hover:bg-brand-700 hover:-translate-y-0.5 focus-visible:ring-brand-600',
        'ghost'    => 'bg-white text-slate-800 ring-1 ring-slate-200 hover:ring-slate-300 hover:bg-slate-50 focus-visible:ring-brand-600',
        'white'    => 'bg-white text-brand-700 shadow-card hover:-translate-y-0.5 focus-visible:ring-white',
        'whatsapp' => 'bg-whatsapp-500 text-white shadow-card hover:bg-whatsapp-600 hover:-translate-y-0.5 focus-visible:ring-whatsapp-600',
    ];

    $classes = $base . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
