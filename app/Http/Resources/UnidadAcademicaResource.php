<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UnidadAcademicaResource extends JsonResource
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
            'UnidadAcademica_id' => $this->id,
            'UnidadAcademica_codigo' => $this->codigo,
            'UnidadAcademica_nombre' => $this->nombre,
            'estado' => $this->estado,
        ];
    }
}
