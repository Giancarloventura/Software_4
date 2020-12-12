<?php

namespace App\Http\Controllers;

use App\Mail\NotificacionEvaluacion;
use App\Mail\CorreccionEvaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevaEtapa;

class MailController extends Controller {
    public function nuevaEtapa() {
        $u = 'Giancarlo';
        $e = 'xd';
        Mail::to('rsaj97@gmail.com')->queue(new NuevaEtapa($u, $e));
        return true;
    }

    public function notificacionPrueba(){
        $rol = "Jefe de laboratorio";
        $nombre = "Rogelio Alfaro";
        $nombre_fase = "Prueba de entrada";
        $laboratorio = "Programación en PHP";
        Mail::to('rsaj97@gmail.com')->queue(new NotificacionEvaluacion($rol, $nombre, $nombre_fase, $laboratorio));
        return true;
    }

    public function correccionPrueba(){
        $nombre_fase = "Prueba de entrada";
        $nombre_laboratorio = "Programación en PHP";
        Mail::to('rsaj97@gmail.com')->queue(new CorreccionEvaluacion($nombre_fase, $nombre_laboratorio));
        return true;
    }
}
