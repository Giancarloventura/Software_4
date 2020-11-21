<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListarHistoricoRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'idUsuario' => ['required']
        ];
    }
}
