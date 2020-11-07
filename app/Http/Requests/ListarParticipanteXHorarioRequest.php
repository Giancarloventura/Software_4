<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListarParticipanteXHorarioRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'codigoCurso' => ['required'],
            'horario' => ['required'],
            'semestre' => ['required']
        ];
    }
}
