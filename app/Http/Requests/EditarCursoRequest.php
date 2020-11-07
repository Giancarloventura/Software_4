<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditarCursoRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'idCurso' => ['required'],
            'codigo' => ['required'],
            'nombre' => ['required']
        ];
    }
}
