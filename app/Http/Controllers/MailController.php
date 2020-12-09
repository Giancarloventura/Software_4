<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevaEtapa;

class MailController extends Controller {
    public function nuevaEtapa() {
        $u = 'Giancarlo';
        $e = 'Tu kchero';
        Mail::to('g.ventura@pucp.edu.pe')->queue(new NuevaEtapa($u, $e));
        return true;
    }
}
