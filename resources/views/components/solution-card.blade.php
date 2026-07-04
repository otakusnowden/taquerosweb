@props([
    'solution',
])

@php
    $available = $solution->isAvailable();
    $featured = $solution->is_flagship;
@endphp

<div @class([
    'group relative flex flex-col rounded-2xl border p-7 transition-all duration-300 reveal',
    'border-brand-200 bg-brand-50/40 shadow-card hover:shadow-card-hover hover:-translate-y-1' => $featured,
    'border-slate-100 bg-white shadow-card hover:shadow-card-hover hover:-translate-y-1' => $available && ! $featured,
    'border-slate-100 bg-slate-50/60' => ! $available,
])>
    @if ($solution->badge && $available)
        <div class="absolute -top-3 left-7">
            <x-badge tone="cta">{{ $solution->badge }}</x-badge>
        </div>
    @endif

    <span @class([
        'flex h-12 w-12 items-center justify-center rounded-xl',
        'bg-brand-600 text-white' => $featured,
        'bg-brand-50 text-brand-600' => $available && ! $featured,
        'bg-slate-200 text-slate-400' => ! $available,
    ])>
        <x-icon :name="$solution->icon" class="w-6 h-6" />
    </span>

    <h3 class="mt-5 text-lg font-semibold text-slate-900">
        {{ $solution->name }}
    </h3>

    <p class="mt-2 flex-1 text-[0.95rem] leading-relaxed text-slate-600">{{ $solution->tagline }}</p>

    <div class="mt-5">
        @if ($available)
            <a href="{{ route('solution', $solution) }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700 transition group-hover:gap-2.5">
                Conocer más
                <x-icon name="arrow-right" class="w-4 h-4" />
            </a>
        @else
            <a href="{{ route('contacto') }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 transition group-hover:gap-2.5 group-hover:text-brand-600">
                Conocer más
                <x-icon name="arrow-right" class="w-4 h-4" />
            </a>
        @endif
    </div>
</div>
