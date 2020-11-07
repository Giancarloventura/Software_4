<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CopiarEvaluacionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'idEvaluacionCopia' => ['required'],
            'idHorario' => ['required']
        ];
    }
}
