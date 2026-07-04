@props([
    'eyebrow' => null,
    'title' => null,
    'align' => 'center', // center | left
    'as' => 'h2',
])

<div @class([
    'max-w-2xl',
    'mx-auto text-center' => $align === 'center',
])>
    @if ($eyebrow)
        <span class="eyebrow">{{ $eyebrow }}</span>
    @endif

    @if ($title)
        <{{ $as }} class="mt-4 text-3xl font-bold leading-tight text-slate-900 sm:text-4xl lg:text-[2.75rem]">
            {{ $title }}
        </{{ $as }}>
    @endif

    @if (! empty(trim($slot)))
        <p class="mt-5 text-lg leading-relaxed text-slate-600">
            {{ $slot }}
        </p>
    @endif
</div>
