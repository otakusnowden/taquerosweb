@php
    // Page-specific structured data: FAQ + flagship Service.
    $schemas = [];

    if ($faqs->isNotEmpty()) {
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(fn ($f) => [
                '@type' => 'Question',
                'name' => $f->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer],
            ])->all(),
        ];
    }

    if ($flagship) {
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $flagship->name,
            'description' => $flagship->summary,
            'provider' => ['@type' => 'Organization', 'name' => config('taquerosweb.name')],
            'areaServed' => config('taquerosweb.country'),
            'serviceType' => 'Menú digital para restaurantes',
        ];
    }
@endphp

<x-layouts.app
    title="Menú digital y soluciones para restaurantes"
    description="Creamos el menú digital, la página web y el código QR de tu restaurante. Reservaciones, WhatsApp y dominio gratis el primer año. Vende más sin complicaciones."
    :schemas="$schemas"
    :heroDark="true"
>
    {{-- ============================================================= --}}
    {{-- 1. HERO                                                       --}}
    {{-- ============================================================= --}}
    <section class="relative isolate overflow-hidden text-white">
        {{-- Dark background, HostGator-style ambient scene --}}
        <div class="absolute inset-0 -z-20 bg-[#080b14]" aria-hidden="true"></div>
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(60%_55%_at_78%_22%,rgba(234,88,12,0.28),transparent_60%),radial-gradient(55%_55%_at_18%_30%,rgba(29,78,216,0.30),transparent_60%)]" aria-hidden="true"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-black/70 via-transparent to-black/30" aria-hidden="true"></div>

        <div class="container-app relative flex min-h-[88vh] items-center pt-28 pb-16 sm:min-h-[86vh] lg:min-h-[640px] lg:pt-32 lg:pb-24">
            <div class="grid w-full items-center gap-10 lg:grid-cols-2 lg:gap-8">
                {{-- Copy --}}
                <div class="max-w-xl">
                    {{-- Promo eyebrow --}}
                    <p class="text-sm font-bold uppercase tracking-[0.15em] text-white/70 sm:text-[0.95rem]">
                        <span class="text-cta-500">Soluciones digitales</span>
                    </p>

                    <h1 class="mt-4 text-[2.75rem] font-extrabold leading-[1.02] tracking-tight text-white sm:text-6xl lg:text-[4rem]">
                        Todo para digitalizar
                        <span class="text-cta-500">tu restaurante</span>
                    </h1>

                    {{-- Benefits, HostGator-style with orange checks --}}
                    <div class="mt-7 space-y-3">
                        <p class="flex items-center gap-2.5 text-lg font-medium text-white/90">
                            <x-icon name="check" class="w-5 h-5 shrink-0 text-cta-500" />
                            Menú digital con todo listo para vender
                        </p>
                        <ul role="list" class="flex flex-wrap items-center gap-x-6 gap-y-2 text-[0.95rem] font-medium text-white/85">
                            @foreach (['Dominio gratis', 'Hosting gratis', 'Soporte en español'] as $benefit)
                                <li class="flex items-center gap-2">
                                    <x-icon name="check" class="w-4 h-4 shrink-0 text-cta-500" />
                                    {{ $benefit }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- CTA + promo --}}
                    <div class="mt-9 flex flex-col gap-4 sm:flex-row sm:items-center">

                        <x-button variant="primary" class="text-base" size="lg" href="https://menudigital.taquerosweb.com" target="_blank" rel="noopener noreferrer">
                            Ver el Menú Digital
                        </x-button>
                        <!--
                        <p class="text-white/90">
                            <span class="text-sm">Hasta</span>
                            <span class="text-2xl font-extrabold text-cta-500">85% OFF</span>
                            <span class="block text-xs text-white/60 sm:inline sm:text-sm">por tiempo limitado*</span>
                        </p>
                        -->
                    </div>

                    {{-- Trust line --}}
                    <div class="mt-7 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-white/60">
                        <span class="inline-flex items-center gap-2">
                            <x-icon name="shield" class="w-5 h-5 text-accent-500" />
                            Soporte via whatsapp
                        </span>
                        <a href="{{ route('servicios') }}" class="inline-flex items-center gap-1.5 font-semibold text-white hover:gap-2.5 transition-all">
                            Ver todas las soluciones
                            <x-icon name="arrow-right" class="w-4 h-4" />
                        </a>
                    </div>
                </div>

                {{-- Visual: cut-out promo graphic floating on the dark scene --}}
                <div class="relative">
                    <div class="pointer-events-none absolute inset-0 mx-auto max-w-lg rounded-full bg-cta-500/15 blur-3xl" aria-hidden="true"></div>
                    <img src="/images/imagen_home.png"
                         alt="TaquerosWeb — Soluciones digitales para restaurantes, hasta 85% de descuento"
                         width="956" height="824" fetchpriority="high"
                         class="relative mx-auto w-full max-w-md drop-shadow-2xl lg:max-w-xl">
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================= --}}
    {{-- 2. PROBLEMA                                                   --}}
    {{-- ============================================================= --}}
    <section class="section bg-white">
        <div class="container-app">
            <x-section-heading eyebrow="El problema" title="Tu comida es excelente. ¿Y tu presencia digital?">
                Cada día, clientes buscan dónde comer desde su celular. Si no te encuentran
                —o lo que ven no convence— terminan eligiendo al de junto.
            </x-section-heading>

            {{-- Carrusel home 16:9 --}}
            @php
                $carruselFiles = collect(glob(public_path('images/carrusel_home/*.{jpg,jpeg,png,webp,gif}'), GLOB_BRACE))
                    ->sort()
                    ->map(fn ($path) => 'images/carrusel_home/' . basename($path))
                    ->values();
            @endphp

            @if ($carruselFiles->isNotEmpty())
                <div
                    x-data="{
                        current: 0,
                        total: {{ $carruselFiles->count() }},
                        next() { this.current = (this.current + 1) % this.total },
                        prev() { this.current = (this.current - 1 + this.total) % this.total },
                    }"
                    class="reveal mt-14"
                >
                    <div class="relative overflow-hidden rounded-2xl bg-slate-900 shadow-card">
                        {{-- Slides track --}}
                        <div class="aspect-video">
                            <div class="flex h-full transition-transform duration-500 ease-out"
                                 :style="`transform: translateX(-${current * 100}%)`">
                                @foreach ($carruselFiles as $i => $file)
                                    <img src="{{ asset($file) }}"
                                         alt="Ejemplo de restaurante digitalizado con TaquerosWeb {{ $i + 1 }}"
                                         width="1280" height="720"
                                         loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                                         class="h-full w-full shrink-0 object-cover">
                                @endforeach
                            </div>
                        </div>

                        @if ($carruselFiles->count() > 1)
                            {{-- Prev button --}}
                            <button type="button" x-on:click="prev()" aria-label="Imagen anterior"
                                class="absolute left-3 top-1/2 -translate-y-1/2 flex h-11 w-11 items-center justify-center rounded-full bg-black text-white shadow-lg ring-1 ring-white/20 transition hover:bg-black/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white sm:left-4 sm:h-14 sm:w-14">
                                <svg class="h-6 w-6 sm:h-7 sm:w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M15 18l-6-6 6-6" />
                                </svg>
                            </button>

                            {{-- Next button --}}
                            <button type="button" x-on:click="next()" aria-label="Imagen siguiente"
                                class="absolute right-3 top-1/2 -translate-y-1/2 flex h-11 w-11 items-center justify-center rounded-full bg-black text-white shadow-lg ring-1 ring-white/20 transition hover:bg-black/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white sm:right-4 sm:h-14 sm:w-14">
                                <svg class="h-6 w-6 sm:h-7 sm:w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 6l6 6-6 6" />
                                </svg>
                            </button>

                            {{-- Dots --}}
                            <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 gap-2">
                                @foreach ($carruselFiles as $i => $file)
                                    <button type="button" x-on:click="current = {{ $i }}"
                                        aria-label="Ir a la imagen {{ $i + 1 }}"
                                        class="h-2.5 rounded-full transition-all"
                                        :class="current === {{ $i }} ? 'w-6 bg-white' : 'w-2.5 bg-white/50 hover:bg-white/80'">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- ============================================================= --}}
    {{-- 3. SOLUCIÓN                                                   --}}
    {{-- ============================================================= --}}
    <section class="section bg-brand-900 text-white">
        <div class="container-app">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div class="max-w-xl">
                    <span class="eyebrow text-cta-400">La solución</span>
                    <h2 class="mt-4 text-3xl font-bold leading-tight text-white sm:text-4xl">
                        Una sola plataforma para que tu restaurante venda en internet.
                    </h2>
                    <p class="mt-5 text-lg leading-relaxed text-slate-300">
                        TaquerosWeb reúne todo lo que necesitas —página, menú, reservaciones,
                        WhatsApp y QR— en un producto que se ve profesional y que tú controlas.
                        Nosotros lo dejamos listo; tú solo te encargas de cocinar rico.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">

                        <x-button variant="white" size="lg" href="https://menudigital.taquerosweb.com" target="_blank" rel="noopener noreferrer">
                            Ver el Menú Digital
                        </x-button>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ([
                        ['icon' => 'lightning', 'title' => 'Listo en días', 'text' => 'Sin esperas eternas ni procesos complicados.'],
                        ['icon' => 'palette', 'title' => 'Imagen profesional', 'text' => 'Diseño cuidado y manual de identidad incluido.'],
                        ['icon' => 'globe', 'title' => 'Dominio propio', 'text' => 'Tu marca, con dominio gratis el primer año.'],
                        ['icon' => 'shield', 'title' => 'Tú tienes el control', 'text' => 'Edita menú, precios y promos cuando quieras.'],
                    ] as $item)
                        <div class="reveal rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-cta-500/15 text-cta-400">
                                <x-icon :name="$item['icon']" class="w-6 h-6" />
                            </span>
                            <h3 class="mt-4 text-base font-semibold text-white">{{ $item['title'] }}</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-slate-300">{{ $item['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================= --}}
    {{-- 4. BENEFICIOS                                                 --}}
    {{-- ============================================================= --}}
    <section class="section bg-white">
        <div class="container-app">
            <x-section-heading eyebrow="Beneficios" title="Lo que tu restaurante gana con TaquerosWeb">
                No se trata de tener una página. Se trata de vender más, verte mejor y trabajar con menos fricción.
            </x-section-heading>

            <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <x-feature icon="map-pin" title="Más clientes nuevos" tone="brand">
                    Apareces cuando te buscan en Google y Maps, y conviertes esa búsqueda en una visita.
                </x-feature>
                <x-feature icon="calendar" title="Más reservas" tone="brand">
                    Tus comensales apartan mesa desde el celular, sin llamadas ni mensajes perdidos.
                </x-feature>
                <x-feature icon="star" title="Mejor imagen" tone="brand">
                    Una marca que se ve seria genera confianza y justifica mejores precios.
                </x-feature>
                <x-feature icon="clock" title="Menos trabajo" tone="brand">
                    Actualizas tu menú en minutos y organizas pedidos sin saturar tu teléfono.
                </x-feature>
            </div>
        </div>
    </section>

    {{-- ============================================================= --}}
    {{-- 5. CARACTERÍSTICAS (flagship)                                 --}}
    {{-- ============================================================= --}}
    @if ($flagship && $flagship->features)
    <section class="section bg-slate-50">
        <div class="container-app">
            <x-section-heading eyebrow="Producto estrella" title="Menú Digital: todo incluido para empezar a vender">
                Una solución completa, no un PDF con un QR. Esto es lo que recibes desde el primer día.
            </x-section-heading>

            <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($flagship->features as $feature)
                    <x-feature :icon="$feature['icon']" :title="$feature['title']" tone="brand">
                        {{ $feature['text'] }}
                    </x-feature>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <x-button variant="white" size="lg" href="https://menudigital.taquerosweb.com" target="_blank" rel="noopener noreferrer">
                    Ver el Menú Digital
                </x-button>
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================================= --}}
    {{-- 6. PRODUCTOS / SOLUCIONES                                     --}}
    {{-- ============================================================= --}}
    <section class="section bg-white">
        <div class="container-app">
            <x-section-heading eyebrow="Soluciones" title="Empieza con el menú digital. Crece con toda la plataforma.">
                TaquerosWeb evoluciona con tu restaurante. Hoy tu menú digital; mañana, todo lo que necesites para vender más.
            </x-section-heading>

            <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($solutions as $solution)
                    <x-solution-card :solution="$solution" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================= --}}
    {{-- 7. TESTIMONIOS                                                --}}
    {{-- ============================================================= --}}
    @if ($testimonials->isNotEmpty())
    <section class="section bg-slate-50">
        <div class="container-app">
            <x-section-heading eyebrow="Testimonios" title="Restaurantes que ya dieron el paso">
                Historias de negocios que pasaron del papel a una presencia digital que vende.
            </x-section-heading>

            <div class="mt-14 grid gap-6 md:grid-cols-3">
                @foreach ($testimonials as $testimonial)
                    <figure class="reveal flex flex-col rounded-2xl border border-slate-100 bg-white p-7 shadow-card">
                        <div class="flex gap-0.5 text-cta-500" aria-label="{{ $testimonial->rating }} de 5 estrellas">
                            @for ($s = 0; $s < $testimonial->rating; $s++)
                                <x-icon name="star" class="w-5 h-5 fill-current" />
                            @endfor
                        </div>
                        <blockquote class="mt-4 flex-1 text-[0.975rem] leading-relaxed text-slate-700">
                            “{{ $testimonial->quote }}”
                        </blockquote>
                        <figcaption class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-5">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-100 text-base font-bold text-brand-700">
                                {{ \Illuminate\Support\Str::of($testimonial->name)->explode(' ')->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('') }}
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-900">{{ $testimonial->name }}</span>
                                <span class="block text-xs text-slate-500">{{ $testimonial->role }}@if($testimonial->business) · {{ $testimonial->business }}@endif</span>
                            </span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================================= --}}
    {{-- 8. PROMOCIONES (carousel)                                     --}}
    {{-- ============================================================= --}}
    @if ($promotions->isNotEmpty() && false == true)
    <section class="section bg-white">
        <div class="container-app">
            <x-section-heading eyebrow="Promociones" title="Aprovecha lo que tenemos para ti">
                Ofertas pensadas para que estrenar tu presencia digital sea más fácil que nunca.
            </x-section-heading>

            <div class="mt-14">
                <x-carousel :items="$promotions" />
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================================= --}}
    {{-- 9. FAQ                                                        --}}
    {{-- ============================================================= --}}
    @if ($faqs->isNotEmpty())
    <section class="section bg-slate-50">
        <div class="container-app">
            <x-section-heading eyebrow="Preguntas frecuentes" title="Resolvemos tus dudas">
                Y si te queda alguna, escríbenos por WhatsApp. Respondemos el mismo día.
            </x-section-heading>

            <div class="mt-14">
                <x-faq :items="$faqs" />
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================================= --}}
    {{-- 10. CTA FINAL                                                 --}}
    {{-- ============================================================= --}}
    <section class="section bg-white">
        <div class="container-app">
            <div class="relative overflow-hidden rounded-3xl bg-brand-900 px-7 py-16 text-center shadow-card-hover sm:px-12 lg:py-20">
                <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-cta-500/20 blur-3xl" aria-hidden="true"></div>
                <div class="pointer-events-none absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-brand-500/20 blur-3xl" aria-hidden="true"></div>

                <div class="relative mx-auto max-w-2xl">
                    <h2 class="text-3xl font-bold leading-tight text-white sm:text-4xl">
                        Tu restaurante listo para vender en internet, esta semana.
                    </h2>
                    <p class="mt-5 text-lg leading-relaxed text-slate-300">
                        Deja el menú de papel atrás. Estrena tu menú digital con dominio y hosting
                        gratis el primer año, y empieza a recibir más reservas y pedidos.
                    </p>
                    <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">

                        <x-button variant="whatsapp" size="lg" :href="\App\Support\Site::whatsappUrl()" target="_blank" rel="noopener">
                            <x-icon name="phone" class="w-5 h-5" />
                            Hablar por WhatsApp
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
