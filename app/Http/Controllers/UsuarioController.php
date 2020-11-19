<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ParticipanteRequest;
use App\Models\User;
use App\Models\Semestre;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsuarioExport;
use App\Imports\UsuarioImport;
use Illuminate\Support\Collection;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\HorarioResource;
use App\Http\Resources\UnidadAcademicaResource;

class UsuarioController extends Controller
{
    public function crearParticipante(ParticipanteRequest $request)
    {
        $usuario = new User();
        $usuario->nombre = $request->nombre;
        $usuario->apellido_paterno = $request->apellido_paterno;
        $usuario->apellido_materno = $request->apellido_materno;
        $usuario->codigo = $request->codigo;
        $usuario->email = $request->email;
        $usuario->password = '';
        $usuario->save();

        $usuario->roles()->attach($request->rol, ['idtHorario' => $request->horario]);

        return response()->json($usuario, 200);
    }

    public function listarHorarios(Request $request){
        $idSemestre = (isset($request->idSemestre)) ? $request->idSemestre : Semestre::orderBy('fecha_inicio', 'desc')->where('estado', 'ACT')->first()->id;
        $idUsuario = $request->idUsuario;
        $user = User::find($idUsuario);
        if(! isset($user)){
            return response('No se ha encontrado al usuario', 404);
        }
        $horariosMat = $user->horarios()->where('idtSemestre', $idSemestre)->wherePivot('idtRol', 5)->get();
        $horariosDic = $user->horarios()->where('idtSemestre', $idSemestre)->wherePivotIn('idtRol',[3, 4])->get();
        return response()->json([
            'matriculado' => HorarioResource::collection($horariosMat),
            'dictando' => HorarioResource::collection($horariosDic),
        ],201);

    }

    public function login(Request $request){
        $user = User::where('email',$request->email)->where('estado','ACT')->first();

        if(!isset($user)){
            return response('No se ha encontrado al usuario', 404);
        }

        $roles = $user->roles()->groupBy('idtRol')->get();
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
        return response(new UserResource($user), 200);
    }

    public function getEmail(Request $request){
        $user = User::where('codigo', $request->codigo)->first();
        if(isset($user))
            return response()->json(["email" => $user->email], 200);
        else
            return response()->json(["email" => ""], 400);
    }

    public function getUnidaAcademica(Request $request){
        $user = User::find($request->idUsuario);
        //echo $user;
        return response()->json(UnidadAcademicaResource::collection($user->unidadAcademica()->get(),200));
    }

    public function getSemestres(Request $request){
        $user = User::find($request->idUsuario);
        $horarios = $user->horarios()->groupBy('idtSemestre')->get();
        $semestres = new Collection;
        foreach($horarios as $horario){
            $semestre = Semestre::find($horario->idtSemestre);
            $aux = collect(['id' => $semestre->id, 'semestre' => $semestre->semestre]);
            $semestres->push($aux);
        }
        return response()->json($semestres,200);
    }

    public function suspenderUsuario(Request $request){
        try{
            $user = User::findOrFail($request->id);
            $user->estado = 'INA'; //cond login
            $user->save();
            return response()->json(['status' => 'success'], 200);
        }
        catch(Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '\n';
        }
    }

    public function activarUsuario(Request $request){
        try{
            $user = User::findOrFail($request->id);
            $user->estado = 'ACT';
            $user->save();
            return response()->json(['status' => 'success'], 200);
        }
        catch(Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '\n';
        }
    }

    public function listarUsuarios(Request $request){
        try
        {
            $cadena = $request->cadena;
            if($cadena == NULL || $cadena ==''){
                $usuarios = User::select('id','codigo','email','nombre','apellido_paterno','apellido_materno','estado')->get();
                return response()->json($usuarios, 200);
            } else {
                if(is_numeric($cadena)){
                    $usuarios = User::select('id','codigo','email','nombre','apellido_paterno','apellido_materno','estado')->where('codigo',$cadena)->get();
                    return response()->json($usuarios, 200);
                } else {
                    $usuarios = User::select('id','codigo','email','nombre','apellido_paterno','apellido_materno','estado')->where('nombre',$cadena)->get();
                    return response()->json($usuarios, 200);
                }
            }
        }
        catch (Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '\n';
        }
    }

}
