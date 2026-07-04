<x-layouts.app
    title="Iniciar sesión"
    description="Accede a tu panel de TaquerosWeb para administrar tu menú digital, reservaciones y promociones."
    :noindex="true"
>
    <section class="relative overflow-hidden bg-gradient-to-b from-brand-50/70 to-white">
        <div class="container-app flex min-h-[calc(100vh-10rem)] items-center justify-center py-16">
            <div class="w-full max-w-md">
                <div class="text-center">
                    <a href="{{ url('/') }}" class="inline-flex">
                        <img src="/images/logo-a-color.jpeg" alt="{{ config('taquerosweb.name') }}" width="180" height="52" class="mx-auto h-12 w-auto">
                    </a>
                    <h1 class="mt-7 text-3xl font-bold text-slate-900">Bienvenido de nuevo</h1>
                    <p class="mt-2 text-slate-600">Entra a tu panel para administrar tu restaurante.</p>
                </div>

                <div class="mt-8 rounded-3xl border border-slate-100 bg-white p-7 shadow-card sm:p-8">
                    <form @submit.prevent class="space-y-5" novalidate>
                        <x-field label="Correo" name="email" type="email" required autocomplete="email" placeholder="tu@correo.com" />

                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label for="field-password" class="block text-sm font-medium text-slate-700">Contraseña</label>
                                <a href="#" class="text-sm font-medium text-brand-700 hover:underline">¿La olvidaste?</a>
                            </div>
                            <input id="field-password" name="password" type="password" required autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 shadow-sm transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 focus:outline-none">
                        </div>

                        <label class="flex items-center gap-2.5 text-sm text-slate-600">
                            <input type="checkbox" name="remember"
                                   class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500/30">
                            Mantener sesión iniciada
                        </label>

                        <x-button type="submit" variant="primary" size="lg" class="w-full">
                            Iniciar sesión
                        </x-button>
                    </form>

                    <div class="my-6 flex items-center gap-4">
                        <span class="h-px flex-1 bg-slate-200"></span>
                        <span class="text-xs font-medium uppercase tracking-wider text-slate-400">o</span>
                        <span class="h-px flex-1 bg-slate-200"></span>
                    </div>

                    <x-button variant="whatsapp" size="lg" class="w-full" :href="\App\Support\Site::whatsappUrl()" target="_blank" rel="noopener">
                        <x-icon name="phone" class="w-5 h-5" /> ¿Necesitas ayuda? WhatsApp
                    </x-button>
                </div>

                <p class="mt-6 text-center text-sm text-slate-600">
                    ¿Aún no tienes cuenta?
                    <button type="button" x-on:click="$store.contratar.open()" class="font-semibold text-brand-700 hover:underline">
                        Contrata tu menú digital
                    </button>
                </p>
            </div>
        </div>
    </section>
</x-layouts.app>
