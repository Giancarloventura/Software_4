<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'tHorario';

    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'idtSemestre',
        'idtCurso',
        'horario',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'idtCurso');
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'idtHorario');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'idtSemestre');
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'tUsuario_tRol', 'idtHorario', 'idtUsuario');
    }

    public function usuarios_roles()
    {
        return $this->hasMany(UsuarioRol::class, 'idtHorario');
    }
}
















