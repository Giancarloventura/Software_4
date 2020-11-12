<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditarCursoRequest;
use App\Http\Requests\EliminarCursoRequest;
use App\Http\Requests\InsertarCursoRequest;
use App\Http\Requests\ObtenerCursoXCodigoRequest;
use App\Models\Curso;
use App\Models\Semestre;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\Imports\CursosImport;
use App\Http\Requests\ListarCursoXUsuarioRequest;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\HorarioResource;

class CursoController extends Controller
{
    public function insertarCurso(InsertarCursoRequest $request)
    {
        $curso = new Curso();
        $curso->idtUnidadAcademica = $request->idUnidadAcademica;
        $curso->codigo = $request->codigo;
        $curso->nombre = $request->nombre;
        $curso->save();

        return response()->json("Curso insertado correctamente", 200);
    }

    public function editarCurso(EditarCursoRequest $request)
    {
        $curso = Curso::findOrFail($request->idCurso);
        $curso->codigo = $request->codigo;
        $curso->nombre = $request->nombre;
        $curso->save();

        return response()->json("Curso editado correctamente", 200);
    }

    public function eliminarCurso(EliminarCursoRequest $request)
    {
        $curso = Curso::findOrFail($request->idCurso);
        $curso->estado = 'INA';
        $curso->save();

        return response()->json("Curso eliminado correctamente", 200);
    }

    public function listarCursosActuales()
    {
        $semestre_actual = Semestre::orderBy('fecha_inicio', 'desc')->where('estado', 'ACT')->first();

        $cursos = Curso::whereHas('horarios', function($query) use ($semestre_actual) {
            $query->where('idtSemestre', $semestre_actual->id);
        })->with('horarios.semestre')->get();

        return response()->json($cursos, 200);
    }

    public function listarLaboratoriosPorHorario(Request $request)
    {

        $laboratorios = DB::select('call LISTAR_LABORATORIOS_POR_HORARIO(?)', array($request->idtHorario));

        return response()->json($laboratorios, 200);
    }

    public function importarCursosVistaPrevia(Request $request){
        //$data = Excel::toCollection(new CursosImport(2), $request->file('file'));
        $data = (new CursosImport(2))->toCollection($request->file('file'))[0];

        return response($data,200);
    }

    public function importarCursos(Request $request){
        //$data = Excel::toCollection(new CursosImport(2), $request->file('file'));
        DB::beginTransaction();
        $import = new CursosImport($request->idtUnidadAcademica);
        $import->import($request->file('file'));
        //dd($import->errors());
        $errores = new Collection;
        foreach($import->errors()->pluck('errorInfo') as $error){
            $errores->push($error[2]);
        }
        if($import->errors()->count()==0){
            DB::commit();
            return response()->json([
                'message' => "Importacion correcta",
                'errores' => []
            ],200);
        }
        else{
            DB::rollBack();
            return response()->json([
                'message' => "Importacion fallida",
                'errores' => $errores,
            ],200);
        }

    }

    public function listarHorarios(Request $request){
        $curso = Curso::where('codigo',$request->codCurso)->first();

        return response()->json(HorarioResource::collection($curso->horarios()->where('idtSemestre', $request->idSemestre)->where('estado', "ACT")->get()),201);
    }

	//Lista los cursos que esta dictando de un docente
	public function listarCursosXUsuario(ListarCursoXUsuarioRequest $request)
    {
        $curso = Horario::join('tUsuario_tRol', 'tUsuario_tRol.idtHorario', '=', 'tHorario.id')
            ->join('tUsuario', 'tUsuario.id', '=', 'tUsuario_tRol.idtUsuario')
            ->join('tCurso', 'tCurso.id', '=', 'tHorario.idtCurso')
            ->join('tSemestre', 'tSemestre.id', '=', 'tHorario.idtSemestre')
            ->select('tCurso.codigo', 'tCurso.nombre', 'tHorario.horario', 'tSemestre.semestre')
            ->where('tUsuario.codigo', '=', $request->codigoUsuario)
            ->where('tSemestre.semestre', '=', $request->semestre)
            ->whereIn('tUsuario_tRol.idtRol', [3, 4])
            ->where('tHorario.estado', '=', 'ACT')
            ->where('tCurso.estado', '=', 'ACT')
            ->orderBy('tCurso.codigo')
            ->get();

        return response()->json($curso, 200);
    }

    public function obtenerCursoXCodigo(ObtenerCursoXCodigoRequest $request)
    {
        $curso = Curso::select('tCurso.nombre')
            ->where('tCurso.codigo', '=', $request->codigoCurso)
            ->get();

        return response()->json($curso, 200);
    }
}
