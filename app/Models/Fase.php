<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fase extends Model
{
    use HasFactory;

    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $table = 'tFase';

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class, 'idtEvaluacion');
    }

    public function preguntas()
    {
        return $this->belongsToMany(Pregunta::class, 'tFase_tPregunta', 'idtFase', 'idtPregunta');
    }

}
