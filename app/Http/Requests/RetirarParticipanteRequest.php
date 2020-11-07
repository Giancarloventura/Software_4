<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RetirarParticipanteRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'idUsuario' => ['required'],
            'horario' => ['required']
        ];
    }
}
