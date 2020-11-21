<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    public $incrementing = true;
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = null;

    protected $table = 'tComentario';

    protected $fillable = ['idtUsuario', 'idtFase', 'contenido', 'tusuario_id_creacion'];

    //public function Usuarios()
    //{
      //  return $this->belongsTo(Usuario::class, 'idtUsuario');
    //}

    //public function Fases()
    //{
      //  return $this->belongsTo(Fase::class, 'idtFase');
    //}
}
