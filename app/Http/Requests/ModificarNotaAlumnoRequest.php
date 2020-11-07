<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModificarNotaAlumnoRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'idRespuesta' => ['required'],
            'puntaje' => ['required'],
            'comentario' => ['required']
        ];
    }
}
