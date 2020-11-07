<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditarHorarioRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'idHorario' => ['required'],
            'codigoProfesor' => ['required'],
            'emailProfesor' => ['required']
        ];
    }
}
