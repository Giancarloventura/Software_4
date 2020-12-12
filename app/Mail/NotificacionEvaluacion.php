<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionEvaluacion extends Mailable {
    use Queueable, SerializesModels;

    public $rol;
    public $nombre;
    public $nombre_fase;
    public $laboratorio;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($rol, $nombre, $nombre_fase, $laboratorio) {
        $this->rol = $rol;
        $this->nombre = $nombre;
        $this->nombre_fase = $nombre_fase;
        $this->laboratorio = $laboratorio;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->subject('COMENTARIO DE LA EVALUACIÓN')->markdown('emails.notificacion');
    }
}
