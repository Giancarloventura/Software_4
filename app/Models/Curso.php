<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    public $incrementing = true;
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $table = 'tCurso';

    protected $fillable = ['idtUnidadAcademica', 'codigo', 'nombre', 'estado'];

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'idtCurso');
    }
}
