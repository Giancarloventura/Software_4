<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObtenerSemestreXCodigoRequest;
use Illuminate\Http\Request;
use App\Models\Semestre;

class SemestreController extends Controller
{
    public function semestreActual()
    {
        $diaActual = date("Y-m-d H:i:s");

        $semestreActual = Semestre::where('tSemestre.fecha_inicio', '<=', $diaActual)
            ->where('tSemestre.fecha_fin', '>=', $diaActual)->first();

        return response()->json($semestreActual, 200);
    }

    public function listarSemestres()
    {
        try
        {
            $semestres = Semestre::select('id','semestre','fecha_inicio','fecha_fin', 'estado')->orderBy('semestre','DESC')->get();
            foreach($semestres as $semestre){
                if($semestre->estado == "ACT"){
                    $semestre->estado = 1;
                }
                else{
                    $semestre->estado = 0;
                }
            }
            return response()->json($semestres, 200);
        }
        catch(Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '\n';
        }
    }

    public function crearSemestre(Request $request)
    {
        try
        {
            $semestre = new Semestre();
            $semestre->id = $request->id;
            $semestre->semestre = $request->semestre;
            $semestre->estado = 'ACT';
            $semestre->fecha_inicio = $request->fecha_inicio;
            $semestre->fecha_fin = $request->fecha_fin;
            $semestre->estado="ACT";
            $semestre->save();
            return response()->json($semestre);
        }
        catch (Exception $exception)
        {
            echo 'Excepción capturada: ' . $exception->getMessage() . '\n';
        }
    }

    public function editarSemestre(Request $request)
    {
        try
        {
            $semestre = Semestre::findOrFail($request->id);
            $semestre->semestre = $request->semestre;
            $semestre->fecha_inicio = $request->fecha_inicio;
            $semestre->fecha_fin = $request->fecha_fin;
            $semestre->save();
            return response()->json($semestre);
        }
        catch(Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '\n';
        }
    }

    public function eliminarSemestre(Request $request)
    {
        try
        {
            $semestre = Semestre::findOrFail($request->id);
            $semestre->estado = 'INA';
            $semestre->save();
            return response()->json($semestre);
        }
        catch(Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '<br>';
        }
    }

    public function obtenerSemestreXCodigo(ObtenerSemestreXCodigoRequest $request)
    {
        $semestre = Semestre::select('tSemestre.semestre')
            ->where('tSemestre.id', '=', $request->idSemestre)
            ->get();

        return response()->json($semestre[0], 200);
    }
}
