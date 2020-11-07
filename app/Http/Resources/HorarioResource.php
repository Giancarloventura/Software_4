<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Curso;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\User;

class HorarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $curso = Curso::find($this->idtCurso);
        if(isset($this->pivot))
            return [
                'id' => $this->id,
                'idtSemestre' => $this->idtSemestre,
                'idtCurso' => $this->idtCurso,
                'nombreCurso' => $curso->nombre,
                'codigoCurso' => $curso->codigo,
                'horario' => $this->horario,
                'estado' => $this->estado,
                'rol' => Rol::find($this->pivot->idtRol)->nombre,
                'idtRol' => $this->pivot->idtRol,
            ];
        else{
            $datosProfesor = new Collection;
            $idUsuarios = DB::table('tUsuario_tRol')->where('idtHorario', $this->id)->where('idtRol', 3)->get()->pluck('idtUsuario');
            foreach($idUsuarios as $idUsuario){
                $usuario = User::find($idUsuario);
                
                $aux = collect(['codigo' => $usuario->codigo, 'email' => $usuario->email, 'nombre' => $usuario->nombre.' '.$usuario->apellido_paterno.' '.$usuario->apellido_materno]);
                $datosProfesor->push($aux);
            }
            return [
                'id' => $this->id,
                'nombreCurso' => $curso->nombre,
                'codigoCurso' => $curso->codigo,
                'horario' => $this->horario,
                'profesores' => $datosProfesor,
            ];
        }
    }
}
