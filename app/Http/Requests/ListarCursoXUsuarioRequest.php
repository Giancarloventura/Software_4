<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListarCursoXUsuarioRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'codigoUsuario' => ['required'],
            'semestre' => ['required']
        ];
    }
}
