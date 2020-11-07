<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    use HasFactory;

    protected $table = 'tUsuario_tRol';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    public function horario()
    {
        return $this->belongsTo(Horario::class, 'idtHorario');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idtUsuario');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idtRol');
    }
}
