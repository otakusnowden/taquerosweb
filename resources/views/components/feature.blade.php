@props([
    'icon' => 'sparkles',
    'title' => '',
    'tone' => 'brand', // brand | cta | accent
])

@php
    $tones = [
        'brand' => 'bg-brand-50 text-brand-600',
        'cta' => 'bg-cta-500/10 text-cta-600',
        'accent' => 'bg-accent-500/10 text-accent-600',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'group reveal rounded-2xl border border-slate-100 bg-white p-7 shadow-card transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover']) }}>
    <span class="flex h-12 w-12 items-center justify-center rounded-xl {{ $tones[$tone] ?? $tones['brand'] }}">
        <x-icon :name="$icon" class="w-6 h-6" />
    </span>
    <h3 class="mt-5 text-lg font-semibold text-slate-900">{{ $title }}</h3>
    <p class="mt-2 text-[0.95rem] leading-relaxed text-slate-600">{{ $slot }}</p>
</div>
