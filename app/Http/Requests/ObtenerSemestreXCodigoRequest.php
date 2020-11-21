<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ObtenerSemestreXCodigoRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'semestre' => ['required']
        ];
    }
}
