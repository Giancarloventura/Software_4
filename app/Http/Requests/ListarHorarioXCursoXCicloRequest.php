<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListarHorarioXCursoXCicloRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'idSemestre' => ['required'],
            'idCurso' => ['required']
        ];
    }
}
