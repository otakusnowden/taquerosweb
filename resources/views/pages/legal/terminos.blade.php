<x-layouts.app
    title="Términos y Condiciones"
    description="Términos y Condiciones de uso de los servicios de TaquerosWeb."
    :noindex="true"
>
    <section class="bg-white">
        <div class="container-app max-w-3xl py-16 lg:py-20">
            <p class="text-sm font-medium text-brand-700">Legal</p>
            <h1 class="mt-2 text-4xl font-bold text-slate-900">Términos y Condiciones</h1>
            <p class="mt-3 text-sm text-slate-500">Última actualización: {{ now()->translatedFormat('d \d\e F \d\e Y') }}</p>

            <div class="prose-legal mt-10">
                <p>
                    Estos Términos y Condiciones regulan el uso de los servicios ofrecidos por
                    <strong>{{ config('taquerosweb.legal_name') }}</strong> ("TaquerosWeb"). Al contratar o utilizar
                    nuestros servicios, aceptas estos términos en su totalidad.
                </p>

                <h2>1. Servicios</h2>
                <p>
                    TaquerosWeb ofrece soluciones digitales para restaurantes, incluyendo menú digital, páginas web,
                    reservaciones y módulos relacionados. El alcance específico de cada servicio se acuerda al momento
                    de la contratación.
                </p>

                <h2>2. Contratación y pagos</h2>
                <ul>
                    <li>Los precios, promociones y condiciones se confirman antes de la contratación.</li>
                    <li>El dominio y hosting incluidos sin costo aplican durante el primer año, salvo que se indique lo contrario.</li>
                    <li>La renovación de dominio, hosting y módulos premium puede generar costos posteriores.</li>
                </ul>

                <h2>3. Responsabilidades del cliente</h2>
                <p>El cliente se compromete a:</p>
                <ul>
                    <li>Proporcionar información veraz y los materiales necesarios para crear su sitio.</li>
                    <li>Mantener actualizada la información de su menú, precios y promociones.</li>
                    <li>Usar el servicio conforme a la ley y sin infringir derechos de terceros.</li>
                </ul>

                <h2>4. Propiedad y contenido</h2>
                <p>
                    El contenido que el cliente proporciona (textos, imágenes, marca) es de su propiedad. El dominio se
                    registra a nombre del cliente. TaquerosWeb conserva los derechos sobre su plataforma, código y
                    herramientas subyacentes.
                </p>

                <h2>5. Disponibilidad del servicio</h2>
                <p>
                    Trabajamos para mantener el servicio disponible de forma continua, pero no garantizamos una
                    operación libre de interrupciones derivadas de mantenimiento, fallas de terceros o causas de fuerza
                    mayor.
                </p>

                <h2>6. Limitación de responsabilidad</h2>
                <p>
                    TaquerosWeb no será responsable por daños indirectos o pérdidas de ingresos derivadas del uso o la
                    imposibilidad de uso del servicio, en la medida que lo permita la ley aplicable.
                </p>

                <h2>7. Cancelación</h2>
                <p>
                    El cliente puede solicitar la cancelación de su servicio en cualquier momento. Las condiciones de
                    reembolso, si aplican, se definen en el acuerdo de contratación.
                </p>

                <h2>8. Modificaciones</h2>
                <p>
                    Podemos actualizar estos Términos. La versión vigente será siempre la publicada en esta página con
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
