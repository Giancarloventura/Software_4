<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioFase extends Model
{
    use HasFactory;

    protected $table = 'tUsuario_tFase';
    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idtUsuario');
    }

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'idtFase');
    }
}
