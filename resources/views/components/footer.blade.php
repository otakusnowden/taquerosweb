@php
    $servicios = [
        ['label' => 'Menú Digital', 'href' => url('/servicios/menu-digital')],
        ['label' => 'Página Web para Restaurantes', 'href' => url('/servicios')],
        ['label' => 'Reservaciones', 'href' => url('/servicios')],
        ['label' => 'Pedidos en Línea', 'href' => url('/servicios')],
    ];
    $empresa = [
        ['label' => 'Inicio', 'href' => url('/')],
        ['label' => 'Servicios', 'href' => url('/servicios')],
        ['label' => 'Contacto', 'href' => url('/contacto')],
        ['label' => 'Iniciar sesión', 'href' => url('/login')],
    ];
@endphp

<footer class="bg-brand-900 text-slate-300">
    <div class="container-app py-16 lg:py-20">
        <div class="grid gap-12 lg:grid-cols-12">
            {{-- Brand --}}
            <div class="lg:col-span-4">
                <div class="inline-flex items-center gap-3 rounded-2xl bg-white px-4 py-3">
                    <img src="/images/logo-a-color.jpeg" alt="{{ config('taquerosweb.name') }}" width="170" height="48" class="h-10 w-auto" loading="lazy">
                </div>
                <p class="mt-5 max-w-sm text-[0.95rem] leading-relaxed text-slate-400">
                    {{ config('taquerosweb.description') }}
                </p>
                <x-social-links class="mt-6" tone="light" />
            </div>

            {{-- Links --}}
            <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:col-span-8 lg:grid-cols-3">
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Servicios</h3>
                    <ul role="list" class="mt-4 space-y-3 text-[0.95rem]">
                        @foreach ($servicios as $item)
                            <li><a href="{{ $item['href'] }}" class="text-slate-400 transition-colors hover:text-white">{{ $item['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Empresa</h3>
                    <ul role="list" class="mt-4 space-y-3 text-[0.95rem]">
                        @foreach ($empresa as $item)
                            <li><a href="{{ $item['href'] }}" class="text-slate-400 transition-colors hover:text-white">{{ $item['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Contacto</h3>
                    <ul role="list" class="mt-4 space-y-3 text-[0.95rem]">
                        <li>
                            <a href="mailto:{{ config('taquerosweb.email') }}" class="text-slate-400 transition-colors hover:text-white">
                                {{ config('taquerosweb.email') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ \App\Support\Site::whatsappUrl() }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 text-slate-400 transition-colors hover:text-white">
                                <x-icon name="phone" class="w-4 h-4" /> WhatsApp
                            </a>
                        </li>
                        <li class="text-slate-400">{{ config('taquerosweb.city') }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-14 flex flex-col gap-4 border-t border-white/10 pt-8 text-sm text-slate-400 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ config('taquerosweb.legal_name') }}. Todos los derechos reservados.</p>
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                <a href="{{ url('/aviso-de-privacidad') }}" class="transition-colors hover:text-white">Aviso de Privacidad</a>
                <a href="{{ url('/terminos-y-condiciones') }}" class="transition-colors hover:text-white">Términos y Condiciones</a>
            </div>
        </div>
    </div>
</footer>
