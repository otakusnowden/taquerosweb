@component('mail::message')
# Nuevo mensaje de contacto

Recibiste una nueva solicitud desde el formulario de **{{ config('taquerosweb.name') }}**.

@component('mail::panel')
**Nombre:** {{ $data['nombre'] }}
@if(!empty($data['restaurante']))
**Restaurante:** {{ $data['restaurante'] }}
@endif
**Correo:** {{ $data['email'] }}
@if(!empty($data['telefono']))
**WhatsApp / Teléfono:** {{ $data['telefono'] }}
@endif
@endcomponent

@if(!empty($data['mensaje']))
**Mensaje:**

{{ $data['mensaje'] }}
@else
_El visitante no dejó un mensaje._
@endif

@component('mail::button', ['url' => 'mailto:' . $data['email']])
Responder a {{ $data['nombre'] }}
@endcomponent

Gracias,<br>
{{ config('taquerosweb.name') }}
@endcomponent
