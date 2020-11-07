<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListarEvaluacionXHorarioRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'codigoUsuario' => ['required'],
            'codigoCurso' => ['required'],
            'horario' => ['required'],
            'semestre' => ['required']
        ];
    }
}
