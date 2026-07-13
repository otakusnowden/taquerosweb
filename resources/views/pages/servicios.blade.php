<x-layouts.app
    title="Servicios"
    description="Soluciones digitales para restaurantes: menú digital, página web, reservaciones, pedidos en línea, CRM y marketing. Empieza con lo que necesitas y crece a tu ritmo."
>
    {{-- Header --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-brand-50/70 to-white">
        <div class="container-app py-16 sm:py-20 lg:py-24">
            <div class="mx-auto max-w-2xl text-center">
                <x-badge tone="brand">Nuestras soluciones</x-badge>
                <h1 class="mt-5 text-4xl font-bold leading-tight text-slate-900 sm:text-5xl">
                    Todo lo que tu restaurante necesita para vender en internet
                </h1>
                <p class="mt-6 text-lg leading-relaxed text-slate-600">
                    Empieza con el menú digital y suma soluciones conforme tu negocio crece.
                    Una sola plataforma, pensada para restaurantes.
                </p>
            </div>
        </div>
    </section>

    {{-- Solutions grid --}}
    <section class="section bg-white pt-0">
        <div class="container-app">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($solutions as $solution)
                    <x-solution-card :solution="$solution" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="section bg-slate-50 pt-0">
        <div class="container-app">
            <div class="rounded-3xl border border-slate-100 bg-white px-7 py-12 text-center shadow-card sm:px-12 lg:py-16">
                <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">¿No sabes por dónde empezar?</h2>
                <p class="mx-auto mt-4 max-w-xl text-lg text-slate-600">
                    Cuéntanos cómo es tu restaurante y te recomendamos la mejor solución para ti. Sin compromiso.
                </p>
                <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                    <x-button variant="primary" size="lg" href="{{ route('contacto') }}">
                        Contratar ahora
                    </x-button>
                    <x-button variant="whatsapp" size="lg" :href="\App\Support\Site::whatsappUrl()" target="_blank" rel="noopener">
                        <x-icon name="phone" class="w-5 h-5" /> Hablar por WhatsApp
                    </x-button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
