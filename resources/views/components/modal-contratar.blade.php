{{-- Global "Contratar" modal. Opened anywhere via $store.contratar.open(). UI only. --}}
<div x-data x-cloak x-show="$store.contratar.isOpen"
     class="fixed inset-0 z-[60] overflow-y-auto"
     x-on:keydown.escape.window="$store.contratar.close()"
     role="dialog" aria-modal="true" aria-labelledby="contratar-title">

    {{-- Backdrop --}}
    <div x-show="$store.contratar.isOpen" x-transition.opacity
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
         x-on:click="$store.contratar.close()" aria-hidden="true"></div>

    {{-- Centering wrapper: top-aligned on mobile (with room for the navbar), centered from sm up --}}
    <div class="flex min-h-full items-start justify-center p-4 pt-20 sm:items-center sm:p-6">

    {{-- Panel --}}
    <div x-show="$store.contratar.isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
         x-trap.noscroll="$store.contratar.isOpen"
         class="relative w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl">

        {{-- Header --}}
        <div class="relative bg-brand-900 px-7 pb-10 pt-7 text-white">
            <button type="button" class="absolute right-4 top-4 rounded-full p-2 text-white/80 hover:bg-white/10 hover:text-white"
                    x-on:click="$store.contratar.close()" aria-label="Cerrar">
                <x-icon name="close" class="w-5 h-5" />
            </button>
            <span class="eyebrow text-cta-400">Empieza hoy</span>
            <h2 id="contratar-title" class="mt-3 text-2xl font-bold text-white">
                Lleva tu negocio al siguiente nivel
            </h2>
            <p class="mt-2 text-[0.95rem] text-slate-300">
                Elige cómo quieres comenzar. Te acompañamos en cada paso.
            </p>
        </div>

        {{-- Options --}}
        <div class="-mt-5 space-y-3 px-7 pb-7">
            <br>
            <a href="{{ url('/contacto') }}"
               class="group flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-all hover:-translate-y-0.5 hover:border-brand-300 hover:shadow-card-hover">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                    <x-icon name="rocket" class="w-6 h-6" />
                </span>
                <span class="min-w-0">
                    <span class="block font-semibold text-slate-900">Contacto</span>
                    <span class="block text-sm text-slate-500">Cuéntanos qué necesitas y te respondemos el mismo día.</span>
                </span>
                <x-icon name="arrow-right" class="ml-auto w-5 h-5 shrink-0 text-slate-400 transition group-hover:translate-x-1 group-hover:text-brand-600" />
            </a>

            <a href="{{ \App\Support\Site::whatsappUrl() }}" target="_blank" rel="noopener"
               class="group flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-all hover:-translate-y-0.5 hover:border-whatsapp-500 hover:shadow-card-hover">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-whatsapp-500/10 text-whatsapp-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.945C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block font-semibold text-slate-900">Hablar por WhatsApp</span>
                    <span class="block text-sm text-slate-500">Resuelve tus dudas con un asesor ahora.</span>
                </span>
                <x-icon name="arrow-right" class="ml-auto w-5 h-5 shrink-0 text-slate-400 transition group-hover:translate-x-1 group-hover:text-whatsapp-600" />
            </a>

            <p class="pt-2 text-center text-xs text-slate-400">
                Sin compromiso · Respuesta el mismo día
            </p>
        </div>
    </div>
    </div>
</div>
