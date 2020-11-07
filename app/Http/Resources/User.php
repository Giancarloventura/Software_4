<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'codigo' => $this->codigo,
            'email' => $this->email,
            'nombre' => $this->nombre,
            'apellido_paterno' => $this->apellido_paterno,
            'apellido_materno' => $this->apellido_materno,
            'esAdmin' => $this->esAdmin,
            'esAlumno' => $this->esAlumno,
            'esCoordinador' => $this->esCoordinador,
            'esProfesor' => $this->esProfesor,
            'esJL' => $this->esJL,
        ];
    }
}
