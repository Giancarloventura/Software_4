<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditarFaseRequest;
use App\Http\Requests\EliminarFaseRequest;
use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Fase;
use App\Http\Requests\CrearFaseRequest;
use App\Http\Requests\ListarFaseXEvaluacionRequest;

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
            ->get();

        return response()->json($evaluacion, 200);
    }
}
