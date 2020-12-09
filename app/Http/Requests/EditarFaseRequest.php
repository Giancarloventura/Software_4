<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditarFaseRequest extends FormRequest
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
            'id' => ['required'],
            'evaluacion' => ['required', 'exists:tEvaluacion,id'],
            'nombre' => ['required'],
            'descripcion' => ['required'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'hora_inicio'=> ['required'],
            'hora_fin' => ['required'],
            'puntaje' => ['required'],
            'sincrona' => ['required', 'in:0,1'],
            'preguntas_aleatorias' => ['required', 'in:0,1'],
            'preguntas_mostradas' => ['required_if:preguntas_aleatorias,1', 'numeric'],
            'disposicion_preguntas' => ['required', 'numeric'],
            'permitir_retroceso' => ['required', 'in:0,1'],
        ];
    }
}
