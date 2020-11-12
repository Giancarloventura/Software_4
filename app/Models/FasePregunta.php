<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasePregunta extends Model
{
    use HasFactory;

    protected $table = 'tFase_tPregunta';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $hidden = ['id'];

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'idtFase');
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'idtPregunta');
    }
}
