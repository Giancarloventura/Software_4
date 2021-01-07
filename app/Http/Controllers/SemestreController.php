<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObtenerSemestreXCodigoRequest;
use Illuminate\Http\Request;
use App\Models\Semestre;
use Illuminate\Support\Facades\DB;

class SemestreController extends Controller
{
    public function semestreActual()
    {
        $diaActual = date("Y-m-d H:i:s");

        $semestreActual = Semestre::select(DB::raw('*, now()<=fecha_fin && now()>=fecha_inicio as activo'))->where('estado', "ACT")->where('tSemestre.fecha_inicio', '<=', $diaActual)
            ->where('tSemestre.fecha_fin', '>=', $diaActual)->first();

        return response()->json($semestreActual, 200);
    }

    public function listarSemestres()
    {
        try
        {
            $semestres = Semestre::select('id','semestre','fecha_inicio','fecha_fin', DB::raw('now()<=fecha_fin && now()>=fecha_inicio as activo'))->where('estado', "ACT")->orderBy('semestre','DESC')->get();
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
            if(Semestre::where('semestre','=',$request->semestre)->where('estado','=',"ACT")->exists()){
                return response()->json([
                    'insertado'=>false
                ], 200);
            }

            $semestre = new Semestre();
            $semestre->id = $request->id;
            $semestre->semestre = $request->semestre;
            $semestre->fecha_inicio = $request->fecha_inicio;
            $semestre->fecha_fin = $request->fecha_fin;
            $semestre->estado="ACT";
            $semestre->save();
            $semestre->insertado=true;
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
            if(Semestre::where('semestre','=',$request->semestre)->where('estado','=',"ACT")->exists()){
                return response()->json([
                    'insertado'=>false
                ], 200);
            }

            $semestre = Semestre::findOrFail($request->id);
            $semestre->semestre = $request->semestre;
            $semestre->fecha_inicio = $request->fecha_inicio;
            $semestre->fecha_fin = $request->fecha_fin;
            $semestre->save();
            $semestre->insertado=true;
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
        $semestre = Semestre::select(DB::raw('*, now()<=fecha_fin && now()>=fecha_inicio as activo'))->where('estado', "ACT")->where('tSemestre.semestre', '=', $request->semestre)
            ->first();

        return response()->json($semestre, 200);
    }
}
