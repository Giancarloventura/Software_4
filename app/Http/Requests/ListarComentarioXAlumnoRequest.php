<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListarComentarioXAlumnoRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'idFase' => ['required'],
            'idUsuario' => ['required']
        ];
    }
}
