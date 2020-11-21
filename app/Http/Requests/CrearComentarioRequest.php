<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearComentarioRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'idFase' => ['required'],
            'idUsuario' => ['required'],
            'idAutor' => ['required'],
            'comentario' => ['required']
        ];
    }
}
