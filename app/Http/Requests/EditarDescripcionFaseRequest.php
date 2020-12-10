<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditarDescripcionFaseRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'idFase' => ['required'],
            'descripcion' => ['required']
        ];
    }
}
