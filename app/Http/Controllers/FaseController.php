<?php

namespace App\Http\Controllers;
#namespace App\Models;

use App\Http\Requests\CrearComentarioRequest;
use App\Http\Requests\EditarFaseRequest;
use App\Http\Requests\EliminarFaseRequest;
use App\Http\Requests\ListarComentarioXAlumnoRequest;
use App\Http\Requests\ObtenerCantidadPreguntasRequest;
use App\Models\Comentario;
use App\Models\Evaluacion;
use App\Models\FasePregunta;
use App\Models\Pregunta;
use App\Models\UsuarioRol;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Respuesta;
use App\Models\Fase;
use App\Models\User;
use App\Http\Requests\CrearFaseRequest;
use App\Http\Requests\ListarFaseXEvaluacionRequest;
use DB;



class FaseController extends Controller
{
    public function crearFase(CrearFaseRequest $request)
    {
        if($request->fecha_inicio == $request->fecha_fin){
            if($request->hora_fin <= $request->hora_inicio){
                return response()->json("Hora fin menor a la hora inicio", 200);
            }
        }

        $fase = new Fase;
        $fase->idtEvaluacion = $request->evaluacion;
        $fase->nombre = $request->nombre;
        $fase->fecha_inicio = $request->fecha_inicio;
        $fase->fecha_fin = $request->fecha_fin;
        $fase->hora_inicio = $request->hora_inicio;
        $fase->hora_fin = $request->hora_fin;
        $fase->sincrona = $request->sincrona;
        $fase->preguntas_aleatorias = $request->preguntas_aleatorias;
        $fase->preguntas_mostradas = $request->preguntas_mostradas;
        $fase->disposicion_preguntas = $request->disposicion_preguntas;
        $fase->permitir_retroceso = $request->permitir_retroceso;
        $fase->save();

        return response()->json($fase, 200);
    }

    public function editarFase(EditarFaseRequest $request)
    {
        if($request->fecha_inicio == $request->fecha_fin){
            if($request->hora_fin <= $request->hora_inicio){
                return response()->json("Hora fin menor a la hora inicio", 200);
            }
        }

        $fase = Fase::findOrFail($request->id);
        $fase->idtEvaluacion = $request->evaluacion;
        $fase->nombre = $request->nombre;
        $fase->fecha_inicio = $request->fecha_inicio;
        $fase->fecha_fin = $request->fecha_fin;
        $fase->hora_inicio = $request->hora_inicio;
        $fase->hora_fin = $request->hora_fin;
        $fase->sincrona = $request->sincrona;
        $fase->preguntas_aleatorias = $request->preguntas_aleatorias;
        $fase->preguntas_mostradas = $request->preguntas_mostradas;
        $fase->disposicion_preguntas = $request->disposicion_preguntas;
        $fase->permitir_retroceso = $request->permitir_retroceso;
        $fase->save();

        return response()->json("Fase editada correctamente", 200);
    }

    //Borra la fase completamente, porque no hay un estado en la tabla
    public function eliminarFase(EliminarFaseRequest $request)
    {
        if($fase = Fase::where("tFase.id", $request->id)->count() == 0){ // La fase no existe
            return response()->json("La fase ingresada no existe", 200);
        }
        else{
            $retirar = Fase::destroy($request->id);
            return response()->json("Fase eliminada exitosamente", 200);
        }


    }

    public function agregarPreguntaXFase(Request $request){
        try{

            //$usuario = User::select('id')->where('codigo', $request->codigo)->first();

            /*if(is_null($usuario))
            {
                $usuario = new User();
                $usuario->email = $request->email;
                $usuario->codigo = $request->codigo;

                $usuario->save();
            }*/

            $pregunta = new Pregunta();
            $fasePregunta = new FasePregunta();

            //Preguntas:
            //$pregunta->id = $request->idPregunta;
            $pregunta->tipo = $request->tipo;
            $pregunta->estado = 'ACT';
            if($pregunta->tipo==0){
                $pregunta->tipo_marcado = NULL;
            } else{
                $pregunta->tipo_marcado = $request->tipo_marcado;
            }
            $pregunta->posicion = $request->posicion;

            //$pregunta->tusuario_id_creacion = $usuario->id;
            //$pregunta->tusuario_id_creacion = $request->tusuario_id_creacion;
            $pregunta->fecha_actualizacion = NULL;
            $pregunta->save();

            //FaseXPregunta:
            $fasePregunta->idtFase = $request->idFase;
            $fasePregunta->idtPregunta = $pregunta->id;
            //$fasePregunta->tusuario_id_creacion = $usuario->id;
            //$fasePregunta->tusuario_id_creacion = $request->tusuario_id_creacion;
            $fasePregunta->fecha_actualizacion = NULL;
            $fasePregunta->save();
            return response()->json($fasePregunta);

        }catch (Exception $exception){
            echo 'Excepción capturada: ' . $exception->getMessage() . '\n';
        }
    }

    public function listarFases($id)
    {
        $fases = Fase::where('idtEvaluacion', $id)->get();

        return response()->json($fases, 200);
    }

	public function listarFasesXEvaluacion(ListarFaseXEvaluacionRequest $request)
    {
        $evaluacion = Horario::join('tUsuario_tRol', 'tUsuario_tRol.idtHorario', '=', 'tHorario.id')
            ->join('tUsuario', 'tUsuario.id', '=', 'tUsuario_tRol.idtUsuario')
            ->join('tCurso', 'tCurso.id', '=', 'tHorario.idtCurso')
            ->join('tSemestre', 'tSemestre.id', '=', 'tHorario.idtSemestre')
            ->join('tEvaluacion', 'tEvaluacion.idtHorario', '=', 'tHorario.id')
            ->join('tFase', 'tFase.idtEvaluacion', '=', 'tEvaluacion.id')
            ->select('tHorario.horario', 'tFase.id', 'tFase.nombre', 'tFase.fecha_inicio', 'tFase.hora_inicio',
                'tFase.fecha_fin', 'tFase.hora_fin', 'tFase.puntaje', 'tFase.sincrona',
                'tFase.preguntas_aleatorias', 'tFase.preguntas_mostradas', 'tFase.disposicion_preguntas',
                'tFase.permitir_retroceso')
            ->where('tUsuario.codigo', '=', $request->codigoUsuario)
            ->where('tSemestre.semestre', '=', $request->semestre)
            ->where('tCurso.codigo', '=', $request->codigoCurso)
            ->where('tHorario.horario', '=', $request->horario)
            ->where('tEvaluacion.id', '=', $request->evaluacion)
            ->where('tHorario.estado', '=', 'ACT')
            ->where('tCurso.estado', '=', 'ACT')
            ->orderBy('tFase.fecha_inicio')
            ->orderBy('tFase.hora_inicio')
            ->get();

        return response()->json($evaluacion, 200);
    }

    public function obtenerFase(Request $request)
    {
        try
        {
            $fase = Fase::select('id', 'nombre', 'fecha_inicio', 'fecha_fin', 'hora_inicio', 'hora_fin',
                                'puntaje', 'sincrona', 'preguntas_aleatorias', 'preguntas_mostradas',
                                'disposicion_preguntas', 'permitir_retroceso')
                        ->where('id', $request->id)
                        ->first();
            return response()->json($fase, 200);
        }
        catch (Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '\n';
        }
    }


    public function getSeguimiento($id)
    {

        $alumnos  = Fase::find($id)->evaluacion()->first()->horario()->first()->usuarios()->wherePivot('idtRol',5)->get();

        $alumnos_collection = [];

        foreach ($alumnos as $alumno){
            //$last_count = DB::table('tRespuesta')
              //          ->where('tusuario_id_creacion', $alumno->id)
                //        ->where('idtFase', $id)
                  //      ->where('idtPregunta','<',$alumno->respuestas->sortByDesc('fecha_creacion')->first()->idtPregunta)
                    //    ->count()+1;
            $respuesta = Respuesta::where('tusuario_id_creacion', $alumno->id)->where('idtFase', $id)->orderBy('fecha_actualizacion', 'desc')->first();
            if($respuesta == null){
                $ultima = 0;
            }
            else{
                $pregunta = Pregunta::find($respuesta->idtPregunta);
                $ultima = $pregunta->posicion;
            }
            $tmp = [
                'nombre'=> $alumno -> nombre,
                'apellido_parterno'=> $alumno->apellido_paterno,
                'apellido_materno'=> $alumno->apellido_materno,
                'codigo' => $alumno->codigo,
                'preguntas_respondidas_count' => $alumno->respuestas()->where('idtFase', $id)->where('estado','<>',0)->get()->count(),
                'ultima_pregunta' => $ultima,

            ];
            $alumnos_collection[] = $tmp;
        }
        return response()->json($alumnos_collection, 200);
    }

    public function obtenerCantidadPreguntas(ObtenerCantidadPreguntasRequest $request)
    {
        $cantidad = FasePregunta::where('tFase_tPregunta.idtFase', '=', $request->idFase)->count();

        return response()->json($cantidad, 200);
    }

    //Crea un comentario hecho por un alumno para una fase
    public function crearComentario(CrearComentarioRequest $request)
    {
        $comentario = new Comentario();

        $comentario->idtUsuario = $request->idUsuario;
        $comentario->idtFase = $request->idFase;
        $comentario->contenido = $request->comentario;
        $comentario->tusuario_id_creacion = $request->idAutor;
        $comentario->save();

        return response()->json('Comentario creado exitosamente', 200);
    }

    //Listar los comentarios de un alumno en una fase
    public function listarComentarioXAlumno(ListarComentarioXAlumnoRequest $request)
    {
        $comentarios = Comentario::where('tComentario.idtFase', '=', $request->idFase)
            ->where('tComentario.idtUsuario', '=', $request->idUsuario)
            ->orderBy('tComentario.fecha_creacion')
            ->get();

        $fase = Fase::where('tFase.id', '=', $request->idFase)->first();
        $evaluacion = Evaluacion::where('tEvaluacion.id', '=', $fase->idtEvaluacion)->first();

        $collection = [];

        foreach($comentarios as $comentario)
        {
            $alumno = User::findOrFail($comentario->tusuario_id_creacion);
            $rol = UsuarioRol::where('tUsuario_tRol.idtUsuario', '=', $comentario->tusuario_id_creacion)
                ->where('tUsuario_tRol.idtHorario', '=', $evaluacion->idtHorario)->first();

            if($rol->idtRol == 3){
                $esProfesor = 'true';
                $esJp = 'false';
                $esAlumno = 'false';
            }
            else
            {
                if($rol->idtRol == 4){
                    $esProfesor = 'false';
                    $esJp = 'true';
                    $esAlumno = 'false';
                }
                else
                {
                    if($rol->idtRol == 5){
                        $esProfesor = 'false';
                        $esJp = 'false';
                        $esAlumno = 'true';
                    }
                    else{
                        $esProfesor = 'false';
                        $esJp = 'false';
                        $esAlumno = 'false';
                    }
                }
            }

            $autor = [
                'id'=> $comentario->tusuario_id_creacion,
                'nombre' => $alumno->nombre,
                'apellido_paterno'=> $alumno->apellido_paterno,
                'apellido_materno'=> $alumno->apellido_materno,
                'esAlumno'=> $esAlumno,
                'esJL'=> $esJp,
                'esProfesor'=> $esProfesor
            ];

            $tmp= [
                'id'=> $comentario->id,
                'idAlumno'=> $comentario->idtUsuario,
                'idFase'=> $comentario->idtFase,
                'comentario'=> $comentario->contenido,
                'fecha_creacion'=> $comentario->fecha_creacion,
                'autor'=> $autor
            ];

            $collection[]= $tmp;
        }

        return response()->json($collection, 200);
    }

    public function crearPreguntasAleatorias (Request $request)
    {
        try
        {
            $fase = Fase::select('idtEvaluacion', 'preguntas_mostradas')
                    ->from('tFase')
                    ->where('id', $request->idtFase)->first();
            $preguntas = Pregunta::select('p.id as idtPregunta',
                        'p.tipo as tipo',
                        'p.tipo_marcado as tipo_marcado')
                        ->from('tPregunta as p')
                        ->join('tFase_tPregunta as x', 'x.idtPregunta', '=', 'p.id')
                        ->where('x.idtFase', $request->idtFase)
                        ->inRandomOrder()
                        ->limit($fase->preguntas_mostradas)
                        ->get();

            if (is_null($preguntas)) return response()->json([],204);

            foreach ($preguntas as $pregunta)
            {
                $respuesta = new Respuesta();
                $respuesta->idtPregunta = $pregunta->idtPregunta;
                $respuesta->idtEvaluacion = $fase->idtEvaluacion;
                $respuesta->idtFase = $request->idtFase;
                $respuesta->estado = 0;
                $respuesta->es_marcada = $pregunta->tipo;
                $respuesta->tusuario_id_creacion = $request->idtUsuario;
                $respuesta->fecha_actualizacion = NULL;

                $respuesta->save();
            }

            return response()->json(['status' => 'success'], 201);
        }
        catch (Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '\n';
        }
    }
}
