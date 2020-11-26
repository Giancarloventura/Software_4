<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Fase extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(isset($this->esta_corregido))
            return [
                'id' => $this->id,
                'nombre' => $this->nombre,
                'esta_corregido' => $this->esta_corregido,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'hora_inicio' => $this->hora_inicio,
                'hora_fin' => $this->hora_fin,
                'puntaje' => $this->puntaje,
                'sincrona' => $this->sincrona,
                'preguntas_aleatorias' => $this->preguntas_aleatorias,
                'preguntas_mostradas' => $this->preguntas_mostradas,
                'disposicion_preguntas' => $this->disposicion_preguntas,
                'permitir_retroceso' => $this->permitir_retroceso,
                'respuestas_creadas' => $this->respuestas_creadas
            ];
        else
            return [
                'id' => $this->id,
                'nombre' => $this->nombre,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'hora_inicio' => $this->hora_inicio,
                'hora_fin' => $this->hora_fin,
                'puntaje' => $this->puntaje,
                'sincrona' => $this->sincrona,
                'preguntas_aleatorias' => $this->preguntas_aleatorias,
                'preguntas_mostradas' => $this->preguntas_mostradas,
                'disposicion_preguntas' => $this->disposicion_preguntas,
                'permitir_retroceso' => $this->permitir_retroceso,
                'respuestas_creadas' => $this->respuestas_creadas
            ];
    }
}

