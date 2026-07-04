@props([
    'transparent' => false, // render light text over a dark hero until scrolled
])

@php
    $links = [
        ['label' => 'Inicio', 'href' => url('/')],
        ['label' => 'Servicios', 'href' => url('/servicios')],
        ['label' => 'Contacto', 'href' => url('/contacto')],
    ];
    $isActive = fn ($href) => url()->current() === $href;
@endphp

<header
    x-data="{ scrolled: false, mobileOpen: false, transparent: {{ $transparent ? 'true' : 'false' }} }"
    @scroll.window="scrolled = window.scrollY > 8"
    :class="mobileOpen ? 'bg-white shadow-card' : ((transparent && !scrolled) ? 'bg-transparent' : 'bg-white/90 shadow-card backdrop-blur')"
    class="fixed inset-x-0 top-0 z-40 transition-all duration-300"
>
    <nav class="container-app" aria-label="Principal">
        <div class="flex h-16 items-center justify-between lg:h-20">
            {{-- Logo (white over dark hero, full-color once scrolled / on light pages) --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0" aria-label="{{ config('taquerosweb.name') }} — Inicio">
                <img src="{{ $transparent ? '/images/bar_logo.png' : '/images/bar_logo.png' }}"
                     :src="(transparent && !scrolled && !mobileOpen) ? '/images/bar_logo.png' : '/images/bar_logo.png'"
                     alt="{{ config('taquerosweb.name') }}"
                     width="160" height="48" class="h-9 w-auto lg:h-11" fetchpriority="high">
            </a>

            {{-- Desktop nav --}}
            <ul role="list" class="hidden items-center gap-8 lg:flex">
                @foreach ($links as $link)
                    <li>
                        <a href="{{ $link['href'] }}"
                           class="text-[0.95rem] font-medium transition-colors"
                           @if($isActive($link['href'])) aria-current="page" @endif
                           :class="(transparent && !scrolled)
                               ? 'text-white/85 hover:text-white'
                               : '{{ $isActive($link['href']) ? 'text-brand-700' : 'text-slate-600 hover:text-brand-700' }}'">
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Desktop actions --}}
            <div class="hidden items-center gap-3 lg:flex">
                <a href="{{ url('/login') }}" class="text-[0.95rem] font-medium transition-colors"
                   :class="(transparent && !scrolled) ? 'text-white/85 hover:text-white' : 'text-slate-600 hover:text-brand-700'">
                    Iniciar sesión
                </a>
                <x-button variant="primary" size="sm" x-on:click="$store.contratar.open()">
                    Contratar ahora
                </x-button>
            </div>

            {{-- Mobile toggle --}}
            <button type="button" class="lg:hidden -mr-2 inline-flex items-center justify-center rounded-lg p-2 transition-colors"
                    :class="(transparent && !scrolled && !mobileOpen) ? 'text-white' : 'text-slate-700'"
                    x-on:click="mobileOpen = true" aria-label="Abrir menú" :aria-expanded="mobileOpen">
                <x-icon name="menu" class="w-7 h-7" />
            </button>
        </div>
    </nav>

    {{-- Mobile drawer (always light) --}}
    <div x-cloak x-show="mobileOpen" class="lg:hidden">
        <div x-show="mobileOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/40"
             x-on:click="mobileOpen = false" aria-hidden="true"></div>
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="fixed inset-y-0 right-0 z-50 w-[82%] max-w-sm bg-white p-6 shadow-xl"
             role="dialog" aria-modal="true" aria-label="Menú de navegación"
             x-on:keydown.escape.window="mobileOpen = false">
            <div class="flex items-center justify-between">
                <img src="/images/logo-a-color.jpeg" alt="{{ config('taquerosweb.name') }}" width="140" height="40" class="h-9 w-auto">
                <button type="button" class="-mr-2 rounded-lg p-2 text-slate-700" x-on:click="mobileOpen = false" aria-label="Cerrar menú">
                    <x-icon name="close" class="w-6 h-6" />
                </button>
            </div>

            <ul role="list" class="mt-8 space-y-1">
                @foreach ($links as $link)
                    <li>
                        <a href="{{ $link['href'] }}" class="block rounded-xl px-4 py-3 text-lg font-medium text-slate-800 hover:bg-slate-50">
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
                <li>
                    <a href="{{ url('/login') }}" class="block rounded-xl px-4 py-3 text-lg font-medium text-slate-800 hover:bg-slate-50">
                        Iniciar sesión
                    </a>
                </li>
            </ul>

            <div class="mt-8 space-y-3">
                <x-button variant="primary" size="lg" class="w-full" x-on:click="mobileOpen = false; $store.contratar.open()">
                    Contratar ahora
                </x-button>
                <x-button variant="whatsapp" size="lg" class="w-full" :href="\App\Support\Site::whatsappUrl()" target="_blank" rel="noopener">
                    <x-icon name="phone" class="w-5 h-5" /> Hablar por WhatsApp
                </x-button>
            </div>
        </div>
    </div>
</header>

{{-- Spacer so the fixed header doesn't overlap content (skipped over a dark hero, which sits under the nav) --}}
@unless ($transparent)
    <div class="h-16 lg:h-20" aria-hidden="true"></div>
@endunless
