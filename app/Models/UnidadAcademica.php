<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Curso;

/**
 * @property integer $id
 * @property integer $tusuario_id
 * @property integer $tusuario_id_creacion
 * @property integer $tusuario_id_actualizacion
 * @property string $codigo
 * @property string $nombre
 * @property string $estado
 * @property string $fecha_creacion
 * @property string $fecha_actualizacion
 * @property TUsuario usuario
 * @property TCurso[] $cursos
 */
class UnidadAcademica extends Model
{

    public const CREATED_AT = 'fecha_creacion';
    public const UPDATED_AT = 'fecha_actualizacion';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tUnidadAcademica';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['tusuario_id', 'tusuario_id_creacion', 'tusuario_id_actualizacion', 'codigo', 'nombre', 'estado', 'fecha_creacion', 'fecha_actualizacion'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo('App\Models\User', 'tusuario_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cursos()
    {
        return $this->hasMany(Curso::class, 'idtUnidadAcademica');
    }
}
