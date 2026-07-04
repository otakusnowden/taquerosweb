<x-layouts.app
    title="Aviso de Privacidad"
    description="Aviso de Privacidad de TaquerosWeb. Conoce cómo recopilamos, usamos y protegemos tus datos personales."
    :noindex="true"
>
    <section class="bg-white">
        <div class="container-app max-w-3xl py-16 lg:py-20">
            <p class="text-sm font-medium text-brand-700">Legal</p>
            <h1 class="mt-2 text-4xl font-bold text-slate-900">Aviso de Privacidad</h1>
            <p class="mt-3 text-sm text-slate-500">Última actualización: {{ now()->translatedFormat('d \d\e F \d\e Y') }}</p>

            <div class="prose-legal mt-10">
                <p>
                    En <strong>{{ config('taquerosweb.legal_name') }}</strong> ("TaquerosWeb", "nosotros") valoramos y
                    protegemos tu privacidad. Este Aviso describe cómo recopilamos, usamos y resguardamos tus datos
                    personales, en cumplimiento de la Ley Federal de Protección de Datos Personales en Posesión de los
                    Particulares (LFPDPPP) de México.
                </p>

                <h2>1. Responsable de tus datos</h2>
                <p>
                    {{ config('taquerosweb.legal_name') }}, con domicilio en {{ config('taquerosweb.city') }}, es responsable
                    del tratamiento de tus datos personales. Para cualquier asunto relacionado, escríbenos a
                    <a href="mailto:{{ config('taquerosweb.email') }}">{{ config('taquerosweb.email') }}</a>.
                </p>

                <h2>2. Datos que recopilamos</h2>
                <p>Podemos recopilar los siguientes datos cuando contactas o contratas nuestros servicios:</p>
                <ul>
                    <li>Datos de identificación: nombre y nombre de tu restaurante.</li>
                    <li>Datos de contacto: correo electrónico y número de teléfono o WhatsApp.</li>
                    <li>Información sobre tu negocio necesaria para crear tu sitio (menú, ubicación, imágenes).</li>
                </ul>

                <h2>3. Finalidades del tratamiento</h2>
                <p>Usamos tus datos para:</p>
                <ul>
                    <li>Brindarte información y atención sobre nuestros servicios.</li>
                    <li>Crear, configurar y dar soporte a tu menú digital o solución contratada.</li>
                    <li>Enviarte avisos relacionados con tu servicio y, si lo autorizas, promociones.</li>
                </ul>

                <h2>4. Transferencia de datos</h2>
                <p>
                    No vendemos ni rentamos tus datos personales. Podemos compartirlos únicamente con proveedores
                    tecnológicos que nos ayudan a operar el servicio (por ejemplo, hosting o pasarelas de pago), bajo
                    estrictas obligaciones de confidencialidad.
                </p>

                <h2>5. Derechos ARCO</h2>
                <p>
                    Tienes derecho a Acceder, Rectificar, Cancelar u Oponerte al tratamiento de tus datos, así como a
                    revocar tu consentimiento. Para ejercer estos derechos, contáctanos en
                    <a href="mailto:{{ config('taquerosweb.email') }}">{{ config('taquerosweb.email') }}</a>.
                </p>

                <h2>6. Seguridad</h2>
                <p>
                    Implementamos medidas de seguridad administrativas, técnicas y físicas razonables para proteger tus
                    datos contra pérdida, uso indebido o acceso no autorizado.
                </p>

                <h2>7. Cambios a este Aviso</h2>
                <p>
                    Podemos actualizar este Aviso de Privacidad. Publicaremos cualquier cambio en esta misma página con
                    su fecha de actualización.
                </p>

                <p class="mt-8 text-sm text-slate-500">
                    Este documento es un modelo general y no constituye asesoría legal. Te recomendamos validarlo con un
                    profesional antes de su publicación definitiva.
                </p>
            </div>
        </div>
    </section>
</x-layouts.app>
