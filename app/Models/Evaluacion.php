<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'tEvaluacion';

    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    public function horario()
    {
        return $this->belongsTo(Horario::class, 'idtHorario');
    }

    public function fases()
    {
        return $this->hasMany(Fase::class, 'idtEvaluacion');
    }
}
