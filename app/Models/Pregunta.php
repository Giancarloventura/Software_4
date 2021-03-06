<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $table = 'tPregunta';

    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    public function alternativas(){
        return $this->hasMany(AlternativaPregunta::class, 'idtPregunta');
    }

    public function fase()
    {
        return $this->belongsToMany(Fase::class, 'tFase_tPregunta', 'idtPregunta', 'idtFase');
    }

}
