<x-layouts.app
    title="Contacto"
    description="Hablemos de tu restaurante. Escríbenos y te ayudamos a llevar tu negocio al mundo digital con el menú digital de TaquerosWeb."
>
    <section class="relative overflow-hidden bg-gradient-to-b from-brand-50/70 to-white">
        <div class="container-app py-16 sm:py-20 lg:py-24">
            <div class="grid gap-12 lg:grid-cols-12 lg:gap-16">
                {{-- Info --}}
                <div class="lg:col-span-5">
                    <x-badge tone="brand">Contacto</x-badge>
                    <h1 class="mt-5 text-4xl font-bold leading-tight text-slate-900 sm:text-5xl">
                        Hablemos de tu restaurante
                    </h1>
                    <p class="mt-6 text-lg leading-relaxed text-slate-600">
                        Cuéntanos qué necesitas y te respondemos el mismo día. Sin compromiso y sin tecnicismos.
                    </p>

                    <div class="mt-10 space-y-4">
                        <a href="{{ \App\Support\Site::whatsappUrl() }}" target="_blank" rel="noopener"
                           class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-card transition hover:-translate-y-0.5 hover:shadow-card-hover">
                            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-whatsapp-500/10 text-whatsapp-600">
                                <x-icon name="phone" class="w-6 h-6" />
                            </span>
                            <span>
                                <span class="block font-semibold text-slate-900">WhatsApp</span>
                                <span class="block text-sm text-slate-500">La forma más rápida de empezar</span>
                            </span>
                        </a>
                        <a href="mailto:{{ config('taquerosweb.email') }}"
                           class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-card transition hover:-translate-y-0.5 hover:shadow-card-hover">
                            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                                <x-icon name="globe" class="w-6 h-6" />
                            </span>
                            <span>
                                <span class="block font-semibold text-slate-900">Correo</span>
                                <span class="block text-sm text-slate-500">{{ config('taquerosweb.email') }}</span>
                            </span>
                        </a>
                    </div>

                    <div class="mt-8">
                        <p class="text-sm font-medium text-slate-500">Síguenos</p>
                        <x-social-links class="mt-3" tone="dark" size="w-6 h-6" />
                    </div>
                </div>

                {{-- Form --}}
                <div class="lg:col-span-7">
                    <div class="rounded-3xl border border-slate-100 bg-white p-7 shadow-card sm:p-9">
                        @if (session('contacto_enviado'))
                            {{-- Success state --}}
                            <div class="py-10 text-center">
                                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-accent-500/10 text-accent-600">
                                    <x-icon name="check" class="w-8 h-8" />
                                </span>
                                <h2 class="mt-5 text-2xl font-bold text-slate-900">¡Gracias por escribirnos!</h2>
                                <p class="mx-auto mt-3 max-w-md text-slate-600">
                                    Recibimos tu mensaje y te responderemos muy pronto. Para una respuesta inmediata, también puedes escribirnos por WhatsApp.
                                </p>
                                <div class="mt-7">
                                    <x-button variant="whatsapp" size="lg" :href="\App\Support\Site::whatsappUrl()" target="_blank" rel="noopener">
                                        <x-icon name="phone" class="w-5 h-5" /> Continuar por WhatsApp
                                    </x-button>
                                </div>
                            </div>
                        @else
                            <form method="POST" action="{{ route('contacto.enviar') }}" class="space-y-5">
                                @csrf

                                @if ($errors->any())
                                    <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                        Revisa los campos marcados e inténtalo de nuevo.
                                    </div>
                                @endif

                                <div class="grid gap-5 sm:grid-cols-2">
                                    <x-field label="Tu nombre" name="nombre" required autocomplete="name" placeholder="Ej. María López" />
                                    <x-field label="Nombre del restaurante" name="restaurante" placeholder="Ej. Antojitos La Esquina" />
                                </div>
                                <div class="grid gap-5 sm:grid-cols-2">
                                    <x-field label="Correo" name="email" type="email" required autocomplete="email" placeholder="tu@correo.com" />
                                    <x-field label="WhatsApp / Teléfono" name="telefono" type="tel" autocomplete="tel" placeholder="55 1234 5678" />
                                </div>
                                <x-field label="¿Cómo te ayudamos?" name="mensaje" as="textarea" placeholder="Cuéntanos sobre tu restaurante y qué necesitas…" />

                                {{-- Honeypot: hidden from humans, catches bots --}}
                                <div class="hidden" aria-hidden="true">
                                    <label>No llenar este campo
                                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                                    </label>
                                </div>

                                <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center">
                                    <x-button type="submit" variant="primary" size="lg" class="sm:w-auto">
                                        Enviar mensaje
                                        <x-icon name="arrow-right" class="w-5 h-5" />
                                    </x-button>
                                    <p class="text-xs text-slate-400">
                                        Al enviar aceptas nuestro
                                        <a href="{{ route('privacy') }}" class="underline hover:text-slate-600">Aviso de Privacidad</a>.
                                    </p>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
