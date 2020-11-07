<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParticipanteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre' => ['required'],
            'codigo' => ['required', 'size:8'],
            'email' => ['required', 'email'],
            'rol' => ['required', 'exists:tRol,id'],
            'horario' => ['required', 'exists:tHorario,id'],
        ];
    }
}
