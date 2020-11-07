<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertarHorarioRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'codigoSemestre' => ['required'],
            'codigoCurso' => ['required'],
            'codigoProfesor' => ['required'],
            'emailProfesor' => ['required'],
            'horario' => ['required']
        ];
    }
}
