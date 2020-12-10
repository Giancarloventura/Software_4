<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevaEtapa;

class MailController extends Controller {
    public function nuevaEtapa() {
        $u = 'Giancarlo';
        $e = 'xd';
        Mail::to('hml101299@gmail.com')->queue(new NuevaEtapa($u, $e));
        return true;
    }
}
