@component('mail::message')
# ¡Gracias por escribirnos, {{ $data['nombre'] }}!

Recibimos tu mensaje y un asesor de **{{ config('taquerosweb.name') }}** te responderá muy pronto.

Mientras tanto, si quieres una respuesta inmediata puedes escribirnos por WhatsApp:

@component('mail::button', ['url' => $whatsapp, 'color' => 'success'])
Hablar por WhatsApp
@endcomponent

@if(!empty($data['mensaje']))
Esto fue lo que nos enviaste:

@component('mail::panel')
{{ $data['mensaje'] }}
@endcomponent
@endif

Un saludo,<br>
El equipo de {{ config('taquerosweb.name') }}

@slot('subcopy')
@component('mail::subcopy')
Recibiste este correo porque alguien usó tu dirección en el formulario de contacto de {{ config('taquerosweb.domain') }}. Si no fuiste tú, puedes ignorarlo.
@endcomponent
@endslot
@endcomponent
