<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CorreccionEvaluacion extends Mailable {
    use Queueable, SerializesModels;

    public $nombre_fase;
    public $nombre_laboratorio;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre_fase, $nombre_laboratorio) {
        $this->nombre_fase = $nombre_fase;
        $this->nombre_laboratorio = $nombre_laboratorio;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->subject('RESULTADO DE LA EVALUACIÓN')->markdown('emails.correccion');
    }
}
