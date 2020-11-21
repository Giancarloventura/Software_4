<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;

    public $incrementing = true;

    protected $table = 'tRespuesta';

    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'idtPregunta');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'tusuario_id_creacion');
    }

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'idtFase');
    }
    public function alternativas(){
        return $this->belongsToMany(AlternativaPregunta::class, 'tRespuesta_tAlternativa_Pregunta', 'idtRespuesta', 'idtAlternativa');
    }
}
