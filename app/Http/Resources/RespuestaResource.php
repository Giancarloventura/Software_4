<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RespuestaResource extends JsonResource
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
            'idPregunta' => $this->idtPregunta,
            'tipo' => $this->tipo,
            'estado' => $this->estado,
            'puntajeAsignado' => (float) $this->puntaje_obtenido,
            'feedback' => $this->comentario,
            'tipoMarcado' => $this->tipoMarcado,
            'opciones' => $this->opciones,
            'intentos' => $this->numero_intento,
            'texto' => $this->redaccion,
        ];
    }
}
