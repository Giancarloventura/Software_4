<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgregarComentarioAlumnoRequest;
use App\Http\Requests\ModificarNotaAlumnoRequest;
use Illuminate\Http\Request;
use App\Models\Respuesta;
use App\Models\Fase;

class RespuestaController extends Controller
{
    public function modificarNotaAlumno(ModificarNotaAlumnoRequest $request)
    {
        $respuesta = Respuesta::findOrFail($request->idRespuesta);
        $respuesta->puntaje_obtenido = $request->puntaje;
        $respuesta->save();

        return response()->json("Nota modificada correctamente", 200);
    }

    public function agregarComentarioAlumno(AgregarComentarioAlumnoRequest $request)
    {
        $respuesta = Respuesta::findOrFail($request->idRespuesta);
        $respuesta->comentario = $request->comentario;
        $respuesta->save();

        return response()->json("Comentario agregado correctamente", 200);
    }

    public function listarPreguntasdeAlumno(Request $request){
        $fase = Fase::findOrFail($request->idFase);
        $evaluacion = $fase->evaluacion()->first();
        $preguntas = $fase->preguntas()->get();
        foreach($preguntas as $pregunta){
            $alternativas = $pregunta->alternativas()->get();
            if (Respuesta::where('idtFase', $request->idFase)->where('idtPregunta', $pregunta->id)->where('tusuario_id_creacion', $request->idUsuario)->exists()) {
                $respuesta = Respuesta::where('idtFase', $request->idFase)->where('idtPregunta', $pregunta->id)->where('tusuario_id_creacion', $request->idUsuario)->first();
                $alternativasRespuesta = $respuesta->alternativas()->get();
            }
            else{
                $respuesta = new Respuesta();
                $respuesta->idtFase = $request->idFase;
                echo $evaluacion;
                $respuesta->idtEvaluacion = $evaluacion->id;
                $respuesta->tusuario_id_creacion = $request->idUsuario;
                $respuesta->tusuario_id_actualizacion = $request->idUsuario;
                $respuesta->idtPregunta = $pregunta->id;
                $respuesta->numero_intento = 0;
                if($alternativas->count()>0){
                    $respuesta->es_marcada = 1;
                    $pregunta->opciones = $alternativas;
                }
                else{
                    $respuesta->es_marcada = 0;
                }
                $respuesta->save();
                $alternativasRespuesta = $respuesta->alternativas()->get();
            }
            foreach($alternativas as $alternativa){
                $alternativa->marcado = 0;
            }
            foreach($alternativasRespuesta as $alternativaRespuesta){
                foreach($alternativas as $alternativa){
                    if($alternativa->id == $alternativaRespuesta->id){
                        $alternativa->marcado = 1;
                        break;
                    }
                }
            }

            $pregunta->respuesta = $respuesta;

        }

        return response()->json($preguntas, 200);

    }

}
