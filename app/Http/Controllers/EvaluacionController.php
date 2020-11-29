<?php

namespace App\Http\Controllers;

use App\Http\Requests\CopiarEvaluacionRequest;
use App\Http\Requests\ObtenerEvaluacionXCodigoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Evaluacion;
use App\Models\Horario;
use App\Http\Requests\CrearEvaluacionRequest;
use App\Http\Requests\ListarEvaluacionXHorarioRequest;
use App\Models\Pregunta;
use App\Models\FasePregunta;
use App\Models\AlternativaPregunta;
use App\Models\User;
use App\Models\Fase;
use App\Http\Resources\Fase as FaseResource;


class EvaluacionController extends Controller
{
    public function crearEvaluacion(CrearEvaluacionRequest $request)
    {
        $evaluacion = new Evaluacion();
        $evaluacion->nombre = request('nombre');
        $evaluacion->idtHorario = request('horario');
        $evaluacion->save();

        return response()->json($evaluacion, 200);
    }

    public function listarEvaluaciones()
    {
        $evaluaciones = Evaluacion::with(['horario.curso', 'horario.semestre'])->get();

        return response()->json($evaluaciones, 200);
    }

    public function eliminarEvaluacion(Request $request)
    {
        $evaluacion = Evaluacion::find($request->id);
        if($evaluacion == null){ // La fase no existe
            return response()->json("La evaluacion ingresada no existe", 200);
        }
        else{
            $retirar = Evaluacion::destroy($request->id);
            return response()->json("Evaluacion eliminada exitosamente", 200);
        }
    }

	public function listarEvaluacionesXHorario(ListarEvaluacionXHorarioRequest $request)
    {
        $horario = Horario::join('tUsuario_tRol', 'tUsuario_tRol.idtHorario', '=', 'tHorario.id')
            ->join('tUsuario', 'tUsuario.id', '=', 'tUsuario_tRol.idtUsuario')
            ->join('tCurso', 'tCurso.id', '=', 'tHorario.idtCurso')
            ->join('tSemestre', 'tSemestre.id', '=', 'tHorario.idtSemestre')
            ->join('tEvaluacion', 'tEvaluacion.idtHorario', '=', 'tHorario.id')
            ->select('tHorario.horario', 'tSemestre.semestre', 'tEvaluacion.id','tEvaluacion.nombre', 'tEvaluacion.fecha_inicio',
                'tEvaluacion.fecha_fin')
            ->where('tUsuario.codigo', '=', $request->codigoUsuario)
            ->where('tSemestre.semestre', '=', $request->semestre)
            ->where('tCurso.codigo', '=', $request->codigoCurso)
            ->where('tHorario.horario', '=', $request->horario)
            ->where('tHorario.estado', '=', 'ACT')
            ->where('tCurso.estado', '=', 'ACT')
            ->orderBy('tEvaluacion.nombre')
            ->get();

        return response()->json($horario, 200);
    }

    public function obtenerEvaluacionXCodigo(ObtenerEvaluacionXCodigoRequest $request)
    {
        $evaluacion = Evaluacion::select('tEvaluacion.nombre')
            ->where('tEvaluacion.id', '=', $request->codigoEvaluacion)
            ->get();

        return response()->json($evaluacion, 200);
    }

    public function obtenerFasesXEvaluacion(Request $request)
    {
        $evaluacion = Evaluacion::find($request->idEvaluacion);
        $fases = $evaluacion->fases()->orderBy('fecha_inicio')->orderBy('hora_inicio')->get();
        if(isset($request->idUsuario)){
            foreach($fases as $fase){
                if(!(DB::table('tUsuario_tFase')->where('idtUsuario', $request->idUsuario)->where('idtFase', $fase->id)->exists())){
                    $usuario = User::find($request->idUsuario);
                    $usuario->fases()->attach($fase->id);
                    $fase->esta_corregido = 0;
                    $fase->respuestas_creadas = 0;
                }
                else{
                    $aux = DB::table('tUsuario_tFase')->where('idtUsuario', $request->idUsuario)->where('idtFase', $fase->id)->first();
                    $esta_corregido = $aux->esta_corregida;
                    $fase->respuestas_creadas = $aux->respuestas_creadas;
                    if($esta_corregido==1 && $fase->publicacion_notas == 1 && $fase->notas_publicadas==0){
                        $esta_corregido = 0;
                    }
                    $fase->esta_corregido= $esta_corregido;
                }
            }
        }
        return response()->json([
            'nombreEvaluacion'=>$evaluacion->nombre,
            'fases'=>FaseResource::collection($fases),
            'fechaServer'=> date("Y-m-d"),
            'horaServer' => date("H:i:s")
        ], 200);
    }

    public function copiarEvaluacion(CopiarEvaluacionRequest $request)
    {
        //Obtengo el dato de la evaluacion anterior
        $evaluacionACopiar = Evaluacion::findOrFail($request->idEvaluacionCopia);

        //Creo la nueva evaluacion
        $evaluacion = new Evaluacion();
        $evaluacion->idtHorario = $request->idHorario;
        $evaluacion->nombre = $evaluacionACopiar->nombre;
        $evaluacion->puntaje_obtenido = $evaluacionACopiar->puntaje_obtenido;
        $evaluacion->puntaje = $evaluacionACopiar->puntaje;
        $evaluacion->save();

        //Obtengo el id de la evaluacion creada
        $evaluacionCreada = Evaluacion::orderBy('tEvaluacion.id', 'desc')->first();
        $idEvaluacion = $evaluacionCreada->id;

        //Obtener las fases que se desean copiar
        $fasesCopia = Fase::where('tFase.idtEvaluacion', '=', $request->idEvaluacionCopia)->get();
        foreach($fasesCopia as $faseCopia)
        {
            //Crear la nueva fase
            $fase = new Fase();
            $fase->idtEvaluacion = $idEvaluacion;
            $fase->nombre = $faseCopia->nombre;
            $fase->fecha_inicio = $faseCopia->fecha_inicio;
            $fase->fecha_fin = $faseCopia->fecha_fin;
            $fase->hora_inicio = $faseCopia->hora_inicio;
            $fase->hora_fin = $faseCopia->hora_fin;
            $fase->puntaje = $faseCopia->puntaje;
            $fase->sincrona = $faseCopia->sincrona;
            $fase->preguntas_aleatorias = $faseCopia->preguntas_aleatorias;
            $fase->preguntas_mostradas = $faseCopia->preguntas_mostradas;
            $fase->disposicion_preguntas = $faseCopia->disposicion_preguntas;
            $fase->permitir_retroceso = $faseCopia->permitir_retroceso;
            $fase->save();

            //Obtengo el id de la fase creada
            $faseCreada = Fase::orderBy('tFase.id', 'desc')->first();
            $idFase = $faseCreada->id;

            //Obtener las preguntas que se desean copiar
            $idPreguntasCopia =  FasePregunta::where('tFase_tPregunta.idtFase', '=', $faseCopia->id)->get();
            foreach($idPreguntasCopia as $idPreguntaCopia)
            {
                //Crear la nueva pregunta a partir de la ya existente
                $preguntaCopia = Pregunta::findOrFail($idPreguntaCopia->idtPregunta);
                $pregunta = new Pregunta();
                $pregunta->nombre = $preguntaCopia->nombre;
                $pregunta->enunciado = $preguntaCopia->enunciado;
                $pregunta->cant_intentos = $preguntaCopia->cant_intentos;
                $pregunta->puntaje = $preguntaCopia->puntaje;
                $pregunta->tipo = $preguntaCopia->tipo;
                $pregunta->tipo_marcado = $preguntaCopia->tipo_marcado;
                $pregunta->posicion = $preguntaCopia->posicion;
                $pregunta->comentario = $preguntaCopia->comentario;
                $pregunta->tusuario_id_creacion = $preguntaCopia->tusuario_id_creacion;
                $pregunta->tusuario_id_actualizacion = $preguntaCopia->tusuario_id_actualizacion;
                $pregunta->save();

                //Obtener el id de la pregunta creada
                $preguntaCreada = Pregunta::orderBy('tPregunta.id', 'desc')->first();
                $idPregunta = $preguntaCreada->id;

                //Obtenemos las alternativas de la pregunta a copiar
                $alternativasCopia = AlternativaPregunta::where('tAlternativa_Pregunta.idtPregunta', '=', $idPreguntaCopia->idtPregunta)->get();
                foreach($alternativasCopia as $alternativaCopia)
                {
                    //Crear la nueva alternativa a partir de la ya existente
                    $alternativa = new AlternativaPregunta();
                    $alternativa->enunciado = $alternativaCopia->enunciado;
                    $alternativa->ruta_archivo = $alternativaCopia->ruta_archivo;
                    $alternativa->es_imagen = $alternativaCopia->es_imagen;
                    $alternativa->es_correcta = $alternativaCopia->es_correcta;
                    $alternativa->idtPregunta = $idPregunta;
                    $alternativa->tusuario_id_creacion = $alternativaCopia->tusuario_id_creacion;
                    $alternativa->tusuario_id_actualizacion = $alternativaCopia->tusuario_id_actualizacion;
                    $alternativa->save();
                }

                //Creamos el enlace de la fase con la pregunta creada
                $fasePreguntaCopia = FasePregunta::where('tFase_tPregunta.idtFase', '=', $faseCopia->id)
                    ->where('tFase_tPregunta.idtPregunta', '=', $preguntaCopia->id)
                    ->first();
                $fasePregunta = new FasePregunta();
                $fasePregunta->idtFase = $idFase;
                $fasePregunta->idtPregunta = $idPregunta;
                $fasePregunta->tusuario_id_creacion = $fasePreguntaCopia->tusuario_id_creacion;
                $fasePregunta->tusuario_id_actualizacion = $fasePreguntaCopia->tusuario_id_actualizacion;
                $fasePregunta->save();
            }
        }

        return response()->json('Evaluacion copiada exitosamente', 200);
    }

    public function resumenNotasAlumno(Request $request){
        $arregloEval = array();
        $puntaje_tot_eval=0;
        $estaCorregidoEval=true;


        $evaluaciones=Evaluacion::where('idtHorario', '=', $request->idtHorario)->get();

        foreach ($evaluaciones as $evaluacion) {

            $arregloFase=array();
            $fases=Fase::where('idtEvaluacion', '=', $evaluacion->id)->get();

            foreach ($fases as $fase){

                $puntaje_obtenido=DB::table('tUsuario_tFase')
                    ->select(DB::raw('tUsuario_tFase.puntaje_obtenido'))
                    ->where('idtFase','=',$fase->id, 'and')
                    ->where('idtUsuario', '=', $request->idtUsuario)
                    ->first();

                $estaCorregidoFase=DB::table('tUsuario_tFase')
                    ->select(DB::raw('tUsuario_tFase.esta_corregida'))
                    ->where('idtFase','=',$fase->id, 'and')
                    ->where('idtUsuario', '=', $request->idtUsuario)
                    ->first();

                if($estaCorregidoFase == null){
                    $estaCorregidoEval = false;
                    $mi_fase=['nombre'=>$fase->nombre,
                        'puntaje'=>null,
                        'puntajeMax'=>$fase->puntaje,
                        'estaCorregido'=>null];
                }

                else if($estaCorregidoFase->esta_corregida==0 ) {
                    $boolEstaCorregidoFase=false;
                    $estaCorregidoEval = false;

                    $mi_fase=['nombre'=>$fase->nombre,
                        'puntaje'=>null,
                        'puntajeMax'=>$fase->puntaje,
                        'estaCorregido'=>$boolEstaCorregidoFase];
                }
                else if($estaCorregidoFase->esta_corregida==1 ){
                    $boolEstaCorregidoFase=true;
                    $mi_fase=['nombre'=>$fase->nombre,
                        'puntaje'=>$puntaje_obtenido->puntaje_obtenido,
                        'puntajeMax'=>$fase->puntaje,
                        'estaCorregido'=>$boolEstaCorregidoFase];

                    $puntaje_tot_eval+=$puntaje_obtenido->puntaje_obtenido;

                }



                array_push($arregloFase, $mi_fase);
            }

            if($estaCorregidoEval){
                $mi_eval=['nombre'=>$evaluacion->nombre,
                'puntaje'=>$puntaje_tot_eval,
                'puntajeMax'=>$evaluacion->puntaje,
                'listaFases'=>$arregloFase,
                'estaCorregido'=>$estaCorregidoEval,
                'idEvaluacion'=>$evaluacion->id];
            }
            else{
                $mi_eval=['nombre'=>$evaluacion->nombre,
                'puntaje'=>null,
                'puntajeMax'=>$evaluacion->puntaje,
                'listaFases'=>$arregloFase,
                'estaCorregido'=>$estaCorregidoEval,
                'idEvaluacion'=>$evaluacion->id];
            }

            array_push($arregloEval, $mi_eval);


        }

        return response()->json($arregloEval, 200);

    }
}


