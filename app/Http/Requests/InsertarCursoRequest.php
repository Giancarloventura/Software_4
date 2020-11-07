<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertarCursoRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'idUnidadAcademica' => ['required'],
            'codigo' => ['required'],
            'nombre' => ['required']
        ];
    }
}
