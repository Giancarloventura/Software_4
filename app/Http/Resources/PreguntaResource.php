<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PreguntaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_actualizacion' => $this->fecha_actualizacion,
            'nroOrden' => $this->posicion,
            'enunciado' => $this->enunciado,
            'puntajeMax' => (float) $this->puntaje,
            'tipo' => $this->tipo,
            'tipoMarcado' => $this->tipo_marcado,
            'opciones' => $this->opciones,
            'opcionesCorrectas' => $this->opcionesCorrectas,
            'feedback' => $this->feedback,
            'intentosMax' => $this->cant_intentos,
        ];
    }
}
