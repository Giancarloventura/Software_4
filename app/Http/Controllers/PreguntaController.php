<?php

namespace App\Http\Controllers;

use App\Models\AlternativaPregunta;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Pregunta;
use App\Http\Controllers\AlternativaPreguntaController;

class PreguntaController extends Controller
{
    public function agregarPregunta(Request $request)
    {
        try
        {
            $pregunta = new Pregunta();
            $pregunta->id = $request->id;

            $pregunta->enunciado = $request->enunciado;
            $pregunta->cant_intentos = $request->cant_intentos;
            $pregunta->puntaje = $request->puntaje;
            $pregunta->tipo = $request->tipo;

            if($request->tipo==0){
                $pregunta->tipo_marcado = NULL;
                $pregunta->nombre= $request->nombre;

                //$pregunta->tusuario_id_creacion = $usuario->id;
                //$pregunta->tusuario_id_creacion = $request->tusuario_id_creacion;
                $pregunta->fecha_actualizacion = NULL;

                $pregunta->save();
            } else {
                $pregunta->tipo_marcado = $request->tipo_marcado; // 0 o 1
                $pregunta->nombre = NULL;

                //$pregunta->tusuario_id_creacion = $usuario->id;
                //$pregunta->tusuario_id_creacion = $request->tusuario_id_creacion;
                $pregunta->fecha_actualizacion = NULL;

                $pregunta->save();

                $alternativas = $request->alternativas;

                foreach($alternativas as $alternativa){
                    $alt = new AlternativaPregunta();

                    $alt->id = $alternativa['id'];
                    $alt->enunciado = $alternativa['enunciado'];
                    $alt->ruta_archivo = $alternativa['ruta_archivo'];
                    $alt->es_imagen = $alternativa['es_imagen'];
                    $alt->es_correcta = $alternativa['es_correcta'];
                    $alt->idtPregunta = $pregunta->id;

                    //$alt->tusuario_id_creacion = $usuario->id;
                    //$alt->tusuario_id_creacion = $request->tusuario_id_creacion;
                    $alt->fecha_actualizacion = NULL;
                    $alt->save();
                }

            }
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

            if($pregunta->tipo == 0){
                $pregunta->nombre = $request->nombre;
                $pregunta->tipo_marcado = NULL;
                $pregunta->save();
            }else{
                $pregunta->nombre = NULL;
                $pregunta->tipo_marcado = $request->tipo_marcado;
                $pregunta->save();
                //Elimina alternativas existentes x idtPregunta:
                app(AlternativaPreguntaController::class)->eliminarAlternativa($pregunta->id);

                //Agrega alternativas:
                $alternativas = $request->alternativas;

                foreach($alternativas as $alternativa){
                    $alt = new AlternativaPregunta();

                    //$alt->id = $alternativa['id'];
                    $alt->enunciado = $alternativa['enunciado'];
                    $alt->ruta_archivo = $alternativa['ruta_archivo'];
                    $alt->es_imagen = $alternativa['es_imagen'];
                    $alt->es_correcta = $alternativa['es_correcta'];
                    $alt->idtPregunta = $pregunta->id;

                    $alt->fecha_actualizacion = NULL;
                    $alt->save();
                }
            }

            return response()->json($pregunta);
        }
        catch (Exception $exception)
        {
            echo 'Excepción capturada: ' . $exception->getMessage() . '\n';
        }
    }

    public function eliminarPregunta(Request $request){
        //Eliminado Logico

    }
}
