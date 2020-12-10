<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PreguntaResourceProfesor extends JsonResource
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
            'nombre' => $this->nombre,
            'nroOrden' => $this->posicion,
            'enunciado' => $this->enunciado,
            'puntajeMax' => (float) $this->puntaje,
            'tipo' => $this->tipo,
            'feedback' => $this->comentario,
            'tipoMarcado' => $this->tipo_marcado,
            'opciones' => $this->opciones,
            'opcionesCorrectas' => $this->opcionesCorrectas,
            'intentosMax' => $this->cant_intentos,
            'subidaArchivo' => $this->subida_archivos,
            'tipoPenalizacion' => $this->tipo_penalizacion,
        ];
    }
}
