@component('mail::message')
    # Hola, {{ $nombreUsuario }}

    Tienes una nueva etapa corregida:
    <br />
    Etapa: {{ $nombreEtapa }}

    <br />
    Puedes desactivar este tipo de notificaciones desde la configuracion de tu cuenta.
    <br />
    Equipo Mr. Robot
@endcomponent
