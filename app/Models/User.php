<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    //use TwoFactorAuthenticatable;

    public $incrementing = true;

    protected $table = 'tUsuario';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'email',
        'codigo',
        'apellido_paterno',
        'apellido_materno',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = [
        
        //'remember_token',
        //'two_factor_recovery_codes',
        //'two_factor_secret',
    //];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function usuario_rol()
    {
        return $this->hasMany(UsuarioRol::class, 'idtUsuario');
    }

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'tUsuario_tRol', 'idtUsuario', 'idtRol');
    }

    public function horarios()
    {
        return $this->belongsToMany(Horario::class, 'tUsuario_tRol', 'idtUsuario', 'idtHorario')->withPivot('idtRol');;
    }

    public function unidadAcademica()
    {
        return $this->hasOne(UnidadAcademica::class, 'tusuario_id');
    }
}
