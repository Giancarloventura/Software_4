<?php

namespace App\Http\Controllers;

use App\Http\Requests\CopiarEvaluacionRequest;
use App\Http\Requests\ObtenerEvaluacionXCodigoRequest;
use Illuminate\Http\Request;
use App\Models\Evaluacion;
use App\Models\Horario;
use App\Http\Requests\CrearEvaluacionRequest;
use App\Http\Requests\ListarEvaluacionXHorarioRequest;

use App\Http\Resources\Fase;

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

        return response()->json([
            'nombreEvaluacion'=>$evaluacion->nombre,
            'fases'=>Fase::collection($fases)
        ], 200);
    }

    public function copiarEvaluacion(CopiarEvaluacionRequest $request)
    {
        
    }
}


