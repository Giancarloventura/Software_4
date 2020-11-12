<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\User;
use App\Models\Fase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComentarioControlador extends Controller
{
    public function listarComentariosPorFaseAlumno(Request $request){
        $comentarios = DB::select('call LISTAR_COMENTARIOS_POR_FASE(?,?)', array($request->idtFase, $request->idtUsuario));
        $fase = Fase::find($request->idtFase);
        $idHorario = $fase->evaluacion()->first()->horario()->first()->id;

        $arreglo = array();

        /*
        $ideUsuario='_ideUsuario';
        $codigo = '_codigo';
        $nombre = '_nombre';
        $apellido_paterno = '_apellido_paterno';
        $apellido_materno = '_apellido_materno' ;
        $idtRol='_idtRol';
        */

        foreach ($comentarios as $comentario){

            $user = User::find($comentario->tusuario_id_creacion);
            if(!isset($user)){
                return response('No se ha encontrado al usuario', 404);
            }

            $roles = $user->roles()->wherePivot('idtHorario',$idHorario)->groupBy('idtRol')->get();
            $user->esAdmin = 0;
            $user->esAlumno = 0;
            $user->esCoordinador = 0;
            $user->esProfesor = 0;
            $user->esJL = 0;
            if($user->unidadAcademica()->get()->count()){
                $user->esCoordinador = 1;
            }
            foreach($roles as $rol){
                if($rol->nombre == "Admin") $user->esAdmin = 1;
                else if($rol->nombre == "Alumno") $user->esAlumno = 1;
                else if($rol->nombre == "Coordinador") $user->esCoordinador = 1;
                else if($rol->nombre == "Profesor") $user->esProfesor = 1;
                else if($rol->nombre == "Jefe de Laboratorio") $user->esJL = 1;
            }
            /*
            $idtRol= DB::select('SELECT @_idtRol');
            $usuario=DB::select('SELECT @_ideUsuario as _ideUsuario, @_codigo as _codigo,@_nombre as _nombre,@_apellido_paterno as _apellido_paterno, @_apellido_materno as _apellido_materno');
            */
            /*
            if($usuario==null ){
                return response()->json('No se encontro al usuario', 200);
            }
            */
            /*
            if($idtRol==3){
                $esAlumno = false;
                $esProfesor = true;
                $esJL =  false;

            }
            else if($idtRol==4){
                $esAlumno = false;
                $esProfesor = false;
                $esJL =  true;
            }
            else if($idtRol==5){
                $esAlumno = true;
                $esProfesor = false;
                $esJL =  false;
            }
            */

            array_push($arreglo, [
                'id'=>$comentario->id,
                /*'usuario'=>$comentario->tusuario_id_creacion,*/

                'usuario'=>[ 'id'=>$user->id,
                            'codigo'=>$user->codigo,
                            'nombre'=>$user->nombre,
                            'apellido_paterno'=>$user->apellido_paterno,
                            'apellido_materno'=>$user->apellido_materno,
                            'esAlumno'=>$user->esAlumno,
                            'esProfesor'=>$user->esProfesor,
                            'esJL'=>$user->esJL

                ],
                'fecha'=>$comentario->fecha,
                'hora'=>$comentario->hora,
                'comentario'=>$comentario->contenido
            ]);
        }


        return response()->json($arreglo, 200);
    }
}
