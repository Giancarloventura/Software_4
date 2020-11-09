<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $tusuario_id_creacion
 * @property integer $tusuario_id_actualizacion
 * @property string $enunciado
 * @property string $ruta_archivo
 * @property boolean $es_imagen
 * @property boolean $es_correcta
 * @property string $fecha_creacion
 * @property string $fecha_actualizacion
 * @property integer $idtPregunta
 * @property TPreguntum $tPreguntum
 * @property TUsuario $tUsuario
 * @property TUsuario $tUsuario
 * @property TRespuestum[] $tRespuestas
 * @property TRespuestum[] $tRespuestas
 */
class AlternativaPregunta extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tAlternativa_Pregunta';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['tusuario_id_creacion', 'tusuario_id_actualizacion', 'enunciado', 'ruta_archivo', 'es_imagen', 'es_correcta', 'fecha_creacion', 'fecha_actualizacion', 'idtPregunta'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'idtPregunta');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function respuestas()
    {
        return $this->belongsToMany(Respuesta::class, 'tRespuesta_tAlternativa', 'idtAlternativa', 'idtRespuesta');
    }
}
