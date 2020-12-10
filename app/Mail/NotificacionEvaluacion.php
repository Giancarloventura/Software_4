<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NuevaEtapa extends Mailable {
    use Queueable, SerializesModels;

    public $nombreUsuario;
    public $nombreEtapa;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombreUsuario, $nombreEtapa) {
        $this->nombreUsuario = $nombreUsuario;
        $this->nombreEtapa = $nombreEtapa;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->subject('NOTIFICACION NUEVA ETAPA')->markdown('emails.etapa');
    }
}
