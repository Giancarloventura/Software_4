<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgregarParticipanteRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'codigoUsuario' => ['required'],
            'email' => ['required'],
            'horario' => ['required'],
            'rol' => ['required']
        ];
    }
}
