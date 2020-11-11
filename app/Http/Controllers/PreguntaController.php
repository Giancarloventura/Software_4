<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\PreguntaResource;
use App\Http\Resources\AlternativaResource;
use Illuminate\Http\Request;
use App\Models\Fase;
use App\Models\Pregunta;

class PreguntaController extends Controller
{
    public function agregarPregunta(Request $request)
    {
        try
        {

            /*$usuario = User::select('id')->where('codigo', $request->codigo)->first();

            if(is_null($usuario))
            {
                $usuario = new User();
                $usuario->email = $request->email;
                $usuario->codigo = $request->codigo;

                $usuario->save();
            }*/

            $pregunta = new Pregunta();
            $pregunta->id = $request->id;
            $pregunta->enunciado = $request->enunciado;
            $pregunta->cant_intentos = $request->cant_intentos;
            $pregunta->puntaje = $request->puntaje;
            $pregunta->tipo = $request->tipo;
            if($pregunta->tipo==0){
                $pregunta->tipo_marcado = NULL;
            }
            else {
                $pregunta->tipo_marcado = $request->tipo_marcado; // 0 o 1
            }
            $pregunta->posicion = 0;
            //$pregunta->tusuario_id_creacion = $usuario->id;
            //$pregunta->tusuario_id_creacion = $request->tusuario_id_creacion;
            $pregunta->fecha_actualizacion = NULL;
            $pregunta->save();
            return response()->json($pregunta);
        }
        catch (Exception $exception)
        {
            echo 'Excepción capturada: ' . $exception->getMessage() . '\n';
        }
    }

    public function editarPregunta(Request $request)
    {
        try
        {
            $pregunta = Pregunta::findOrFail($request->id);
            $pregunta->enunciado = $request->enunciado;
            $pregunta->cant_intentos = $request->cant_intentos;
            $pregunta->puntaje = $request->puntaje;
            $pregunta->save();
            return response()->json($pregunta);
        }
        catch (Exception $exception)
        {
            echo 'Excepción capturada: ' . $exception->getMessage() . '\n';
        }
    }

    public function listarPreguntasdeProfesor(Request $request){
        $fase = Fase::findOrFail($request->idFase);
        $evaluacion = $fase->evaluacion()->first();
        $preguntas = $fase->preguntas()->get();
        foreach($preguntas as $pregunta){
            $alternativas = $pregunta->alternativas()->get();
            $pregunta->opciones = AlternativaResource::collection($alternativas);
            $pregunta->opcionesCorrectas = $pregunta->alternativas()->where('es_correcta', 1)->get()->count();
        }

        return response()->json(PreguntaResource::collection($preguntas), 200);
    }

}
