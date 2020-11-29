<?php

namespace App\Http\Controllers;

use App\Models\AlternativaPregunta;
use App\Models\User;
use App\Http\Resources\PreguntaResource;
use App\Http\Resources\AlternativaResource;
use Illuminate\Http\Request;
use App\Models\Fase;
use App\Models\Pregunta;
use App\Http\Controllers\AlternativaPreguntaController;

class PreguntaController extends Controller
{
    public function agregarPregunta(Request $request)
    {
        try
        {
            $pregunta = new Pregunta();
            //$pregunta->id = $request->id;

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
            $puntajeOld = $pregunta->puntaje;
            $pregunta->enunciado = $request->enunciado;
            $pregunta->cant_intentos = $request->cant_intentos;
            $pregunta->puntaje = $request->puntaje;
            $pregunta->comentario = $request->feedback;

            $fase = $pregunta->fase()->first();
            if($fase->preguntas_aleatorias==1){
                $fase->puntaje = $pregunta->puntaje*$fase->preguntas_mostradas;
            }
            else{
                $fase->puntaje = $fase->puntaje - $puntajeOld+$pregunta->puntaje;
            }
            $fase->save();

            if($pregunta->tipo == 0){
                $pregunta->nombre = $request->nombre;
                $pregunta->tipo_marcado = NULL;
                $pregunta->save();
            }else{
                $pregunta->nombre = NULL;
                $pregunta->tipo_marcado = $request->tipo_marcado;
                $pregunta->save();
                //Elimina alternativas existentes x idtPregunta:
                $alts = AlternativaPregunta::select('id')->where('idtPregunta',$pregunta->id)->get();
                if($alts <> NULL){
                    app(AlternativaPreguntaController::class)->eliminarAlternativa($pregunta->id);
                }

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

    public function intercambiarOrden(Request $request)
    {
        try {
            $pregunta1 = Pregunta::findOrFail($request->idtPregunta1);
            $pregunta2 = Pregunta::findOrFail($request->idtPregunta2);

            $aux = $pregunta1->posicion;
            $pregunta1->posicion = $pregunta2->posicion;
            $pregunta2->posicion = $aux;

            $pregunta1->save();
            $pregunta2->save();

            return response()->json(['status' => 'success'], 200);
        }
        catch (Exception $exception)
        {
            echo 'Excepción capturada: ' . $exception->getMessage() . '\n';
        }

    }

    public function listarPreguntasdeProfesor(Request $request){
        $fase = Fase::findOrFail($request->idFase);
        $evaluacion = $fase->evaluacion()->first();
        $preguntas = $fase->preguntas()->where('estado',"ACT")->orderBy('posicion', 'asc')->get();
        foreach($preguntas as $pregunta){
            $alternativas = $pregunta->alternativas()->get();
            $pregunta->opciones = AlternativaResource::collection($alternativas);
            $pregunta->opcionesCorrectas = $pregunta->alternativas()->where('es_correcta', 1)->get()->count();
        }

        return response()->json(PreguntaResource::collection($preguntas), 200);
    }


    public function eliminarPregunta(Request $request){
        //Eliminado Logico
        try{
            $pregunta = Pregunta::findOrFail($request->id);
            $pregunta->estado = 'INA';
            $pregunta->save();
            $preguntas_posteriores = Fase::findOrFail($request->idFase)->preguntas()->where('posicion','>', $pregunta->posicion)->where('estado', "ACT")->get();
            foreach($preguntas_posteriores as $pregunta_posterior){
                $pregunta_posterior->posicion = $pregunta_posterior->posicion-1;
                $pregunta_posterior->save();
            }
            $fase = $pregunta->fase()->first();
            $fase->puntaje = $fase->puntaje-$pregunta->puntaje;
            $fase->save();
            $evaluacion = $fase->evaluacion()->first();
            $evaluacion->puntaje=$evaluacion->puntaje-$pregunta->puntaje;
            $evaluacion->save();
            return response()->json(['status' => 'success'], 200);
        }catch (Exception $exception){
            echo 'Excepción capturada: ' . $exception->getMessage() . '\n';
        }
    }
}
