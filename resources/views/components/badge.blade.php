@props([
    'tone' => 'brand', // brand | cta | accent | slate | white
])

@php
    $tones = [
        'brand' => 'bg-brand-50 text-brand-700 ring-brand-100',
        'cta' => 'bg-cta-500/10 text-cta-700 ring-cta-500/20',
        'accent' => 'bg-accent-500/10 text-accent-600 ring-accent-500/20',
        'slate' => 'bg-slate-100 text-slate-600 ring-slate-200',
        'white' => 'bg-white/10 text-white ring-white/20',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset ' . ($tones[$tone] ?? $tones['brand'])]) }}>
    {{ $slot }}
</span>
