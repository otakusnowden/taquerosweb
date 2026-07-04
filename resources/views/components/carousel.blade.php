@props([
    'items' => collect(),
])

@php
    $items = collect($items)->values();
    $count = $items->count();
@endphp

@if (false)
<div
    x-data="{
        current: 0,
        count: {{ $count }},
        timer: null,
        start() {
            if (this.count < 2) return;
            this.stop();
            this.timer = setInterval(() => this.next(), 6000);
        },
        stop() { if (this.timer) clearInterval(this.timer); },
        next() { this.current = (this.current + 1) % this.count; },
        prev() { this.current = (this.current - 1 + this.count) % this.count; },
        go(i) { this.current = i; },
    }"
    x-init="start()"
    @mouseenter="stop()" @mouseleave="start()"
    class="relative"
    role="region" aria-roledescription="carrusel" aria-label="Promociones"
>
    {{-- Slides --}}
    <div class="relative overflow-hidden rounded-3xl shadow-card">
        @foreach ($items as $i => $promo)
            <div
                x-show="current === {{ $i }}"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                @if(! $loop->first) x-cloak class="absolute inset-0" @endif
                role="group" aria-roledescription="diapositiva"
                aria-label="{{ $i + 1 }} de {{ $count }}"
            >
                <div class="grid items-center gap-0 bg-brand-900 md:grid-cols-2">
                    {{-- Image --}}
                    <div class="relative h-56 md:h-full md:min-h-[22rem]">
                        <img src="{{ $promo->image }}" alt="{{ $promo->title }}"
                             class="h-full w-full object-cover" loading="lazy" width="800" height="500">
                    </div>
                    {{-- Copy --}}
                    <div class="p-8 text-white sm:p-10 lg:p-14">
                        <x-badge tone="cta">Promoción</x-badge>
                        <h3 class="mt-4 text-2xl font-bold text-white sm:text-3xl">{{ $promo->title }}</h3>
                        @if ($promo->copy)
                            <p class="mt-3 text-[0.975rem] leading-relaxed text-slate-300">{{ $promo->copy }}</p>
                        @endif
                        <div class="mt-7">
                            @if ($promo->cta_action === 'whatsapp')
                                <x-button variant="whatsapp" :href="\App\Support\Site::whatsappUrl()" target="_blank" rel="noopener">
                                    {{ $promo->cta_label ?? 'Hablar por WhatsApp' }}
                                </x-button>
                            @elseif ($promo->cta_action === 'url' && $promo->cta_url)
                                <x-button variant="primary" :href="$promo->cta_url">
                                    {{ $promo->cta_label ?? 'Ver más' }}
                                </x-button>
                            @else
                                <x-button variant="primary" x-on:click="$store.contratar.open()">
                                    {{ $promo->cta_label ?? 'Contratar ahora' }}
                                </x-button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($count > 1)
        {{-- Controls --}}
        <button type="button" x-on:click="prev()" aria-label="Anterior"
                class="absolute left-3 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-slate-800 shadow-card transition hover:scale-105 hover:text-brand-700 md:left-5">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </button>
        <button type="button" x-on:click="next()" aria-label="Siguiente"
                class="absolute right-3 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-slate-800 shadow-card transition hover:scale-105 hover:text-brand-700 md:right-5">
            <x-icon name="chevron-right" class="w-5 h-5" />
        </button>

        {{-- Indicators --}}
        <div class="mt-6 flex items-center justify-center gap-2.5" role="tablist" aria-label="Seleccionar promoción">
            @for ($i = 0; $i < $count; $i++)
                <button type="button" x-on:click="go({{ $i }})"
                        :class="current === {{ $i }} ? 'w-8 bg-brand-600' : 'w-2.5 bg-slate-300 hover:bg-slate-400'"
                        class="h-2.5 rounded-full transition-all duration-300"
                        :aria-selected="current === {{ $i }}"
                        role="tab" aria-label="Ir a la promoción {{ $i + 1 }}"></button>
            @endfor
        </div>
    @endif
</div>
@endif
