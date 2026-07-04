@php
    $schemas = [
        [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Servicios', 'item' => route('servicios')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $solution->name, 'item' => route('solution', $solution)],
            ],
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $solution->name,
            'description' => $solution->summary,
            'provider' => ['@type' => 'Organization', 'name' => config('taquerosweb.name')],
            'areaServed' => config('taquerosweb.country'),
        ],
    ];
@endphp

<x-layouts.app
    :title="$solution->name"
    :description="$solution->summary"
    :schemas="$schemas"
>
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-brand-50/70 to-white">
        <div class="container-app py-16 sm:py-20 lg:py-24">
            {{-- Breadcrumb --}}
            <nav class="mb-8 flex items-center gap-2 text-sm text-slate-500" aria-label="Ruta de navegación">
                <a href="{{ url('/') }}" class="hover:text-brand-700">Inicio</a>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
                <a href="{{ route('servicios') }}" class="hover:text-brand-700">Servicios</a>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
                <span class="font-medium text-slate-700" aria-current="page">{{ $solution->name }}</span>
            </nav>

            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div class="max-w-xl">
                    @if ($solution->badge)
                        <x-badge tone="cta">{{ $solution->badge }}</x-badge>
                    @endif
                    <h1 class="mt-5 text-4xl font-bold leading-[1.1] text-slate-900 sm:text-5xl">{{ $solution->name }}</h1>
                    <p class="mt-5 text-xl font-medium text-brand-700">{{ $solution->tagline }}</p>
                    <p class="mt-5 text-lg leading-relaxed text-slate-600">{{ $solution->description ?? $solution->summary }}</p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <x-button variant="primary" size="lg" x-on:click="$store.contratar.open()">
                            Contratar ahora
                            <x-icon name="arrow-right" class="w-5 h-5" />
                        </x-button>
                        <x-button variant="whatsapp" size="lg" :href="\App\Support\Site::whatsappUrl()" target="_blank" rel="noopener">
                            <x-icon name="phone" class="w-5 h-5" /> Preguntar por WhatsApp
                        </x-button>
                    </div>
                </div>

                <div class="relative mx-auto max-w-md">
                    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-card-hover">
                        <img src="/images/banner1.jpg" alt="{{ $solution->name }} de TaquerosWeb"
                             width="800" height="500" fetchpriority="high" class="w-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- What's included --}}
    @if ($solution->includes)
    <section class="section bg-white">
        <div class="container-app">
            <div class="grid gap-12 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <span class="eyebrow">Todo incluido</span>
                    <h2 class="mt-4 text-3xl font-bold text-slate-900">Esto recibes desde el primer día</h2>
                    <p class="mt-4 text-lg text-slate-600">
                        Una solución completa, sin sorpresas ni costos ocultos. Listo para que solo te preocupes por cocinar.
                    </p>
                </div>
                <div class="lg:col-span-8">
                    <ul role="list" class="grid gap-4 sm:grid-cols-2">
                        @foreach ($solution->includes as $item)
                            <li class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-accent-500/10 text-accent-600">
                                    <x-icon name="check" class="w-4 h-4" />
                                </span>
                                <span class="text-[0.95rem] font-medium text-slate-700">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Features --}}
    @if ($solution->features)
    <section class="section bg-slate-50">
        <div class="container-app">
            <x-section-heading eyebrow="Características" title="Pensado para vender más, trabajar menos">
                Cada función está diseñada para resolver un problema real de tu restaurante.
            </x-section-heading>
            <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($solution->features as $feature)
                    <x-feature :icon="$feature['icon']" :title="$feature['title']" tone="brand">
                        {{ $feature['text'] }}
                    </x-feature>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Premium modules --}}
    @if ($solution->premium)
    <section class="section bg-white">
        <div class="container-app">
            <x-section-heading eyebrow="Módulos Premium" title="Crece cuando lo necesites">
                Empieza con lo esencial y suma funciones avanzadas conforme tu restaurante despega. Sin rehacer nada.
            </x-section-heading>
            <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($solution->premium as $module)
                    <div class="reveal rounded-2xl border border-slate-100 bg-white p-7 shadow-card">
                        <div class="flex items-center justify-between">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-cta-500/10 text-cta-600">
                                <x-icon :name="$module['icon']" class="w-6 h-6" />
                            </span>
                            <x-badge tone="cta">Premium</x-badge>
                        </div>
                        <h3 class="mt-5 text-lg font-semibold text-slate-900">{{ $module['title'] }}</h3>
                        <p class="mt-2 text-[0.95rem] leading-relaxed text-slate-600">{{ $module['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- FAQ --}}
    @if ($faqs->isNotEmpty())
    <section class="section bg-slate-50">
        <div class="container-app">
            <x-section-heading eyebrow="Preguntas frecuentes" title="Lo que otros restauranteros preguntan" />
            <div class="mt-14">
                <x-faq :items="$faqs" />
            </div>
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="section bg-white">
        <div class="container-app">
            <div class="relative overflow-hidden rounded-3xl bg-brand-900 px-7 py-16 text-center sm:px-12">
                <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-cta-500/20 blur-3xl" aria-hidden="true"></div>
                <div class="relative mx-auto max-w-2xl">
                    <h2 class="text-3xl font-bold text-white sm:text-4xl">¿Listo para estrenar tu {{ mb_strtolower($solution->name) }}?</h2>
                    <p class="mt-5 text-lg text-slate-300">Empieza hoy con dominio y hosting gratis el primer año.</p>
                    <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <x-button variant="primary" size="lg" x-on:click="$store.contratar.open()">Contratar ahora</x-button>
                        <x-button variant="white" size="lg" href="{{ route('servicios') }}">Ver otras soluciones</x-button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
