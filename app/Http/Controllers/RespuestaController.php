<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgregarComentarioAlumnoRequest;
use App\Http\Requests\ModificarNotaAlumnoRequest;
use Illuminate\Support\Collection;
use App\Http\Resources\PreguntaResource;
use App\Http\Resources\RespuestaResource;
use App\Http\Resources\AlternativaResource;
use Illuminate\Http\Request;
use App\Models\Respuesta;
use App\Models\Fase;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\CorreccionEvaluacion;
use Illuminate\Support\Facades\Storage;
use DB;

class RespuestaController extends Controller
{
    public function modificarNotaComentarioAlumno(Request $request)
    {
        $respuesta = Respuesta::findOrFail($request->idRespuesta);
        $respuesta->puntaje_obtenido = $request->puntaje;
        if(!($request->comentario==null))
            $respuesta->comentario = $request->comentario;
        $respuesta->tUsuario_id_actualizacion = $request->idProfesor;
        $respuesta->estado = 2;
        $respuesta->save();

        if(DB::table('tUsuario_tFase')->where('idtUsuario',$respuesta->tusuario_id_creacion)->where('idtFase',$respuesta->idtFase)->first()->esta_corregida==1){
            $fase = Fase::find($respuesta->idtFase);
            if($fase->publicacion_notas==0 or ($fase->publicacion_notas==1 and $fase->notas_publicadas==1)){
                $evaluacion = $fase->evaluacion()->first();
                $destinatario = User::find($respuesta->tusuario_id_creacion);
                Mail::to($destinatario->email)->queue(new CorreccionEvaluacion($fase->nombre, $evaluacion->nombre));
            }
        }

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
        $preguntas = $fase->preguntas()->where('estado',"ACT")->get();
        $preguntas_respuestas = new Collection;
        foreach($preguntas as $pregunta){
            $alternativas = $pregunta->alternativas()->get();
            $pregunta->opciones = $alternativas;
            if (Respuesta::where('idtFase', $request->idFase)->where('idtPregunta', $pregunta->id)->where('tusuario_id_creacion', $request->idUsuario)->exists()) {
                $respuesta = Respuesta::where('idtFase', $request->idFase)->where('idtPregunta', $pregunta->id)->where('tusuario_id_creacion', $request->idUsuario)->first();
                $alternativasRespuesta = $respuesta->alternativas()->get();
            }
            else{
                $respuesta = new Respuesta();
                $respuesta->idtFase = $request->idFase;
                $respuesta->idtEvaluacion = $evaluacion->id;
                $respuesta->tusuario_id_creacion = $request->idUsuario;
                $respuesta->tusuario_id_actualizacion = $request->idUsuario;
                $respuesta->idtPregunta = $pregunta->id;
                $respuesta->numero_intento = 0;
                $respuesta->puntaje_obtenido - null;
                $respuesta->comentario = $pregunta->comentario;
                $respuesta->fecha_actualizacion = null;
                $respuesta->estado = 0;
                $respuesta->redaccion = null;
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
            $respuesta->subida_archivos = $pregunta->subida_archivos;
            $respuesta->tipo = $pregunta->tipo;
            $respuesta->tipoMarcado = $pregunta->tipo_marcado;
            $respuesta->opciones = AlternativaResource::collection($alternativas);
            $pregunta->opciones = AlternativaResource::collection($alternativas);
            $pregunta->opcionesCorrectas = $pregunta->alternativas()->where('es_correcta', 1)->get()->count();
            $aux = collect(['pregunta' => new PreguntaResource($pregunta), 'respuesta' => new RespuestaResource($respuesta)]);
            $preguntas_respuestas->push($aux);
        }

        return response()->json($preguntas_respuestas, 200);

    }

    public function listarPreguntasAleatoriasdeAlumno(Request $request){
        $respuestas = Respuesta::where('idtFase', $request->idFase)->where('tusuario_id_creacion', $request->idUsuario)->get();
        $preguntas_respuestas = new Collection;
        foreach($respuestas as $respuesta){
            $alternativasRespuesta = $respuesta->alternativas()->get();
            $pregunta = $respuesta->pregunta()->first();
            $alternativas = $pregunta->alternativas()->get();
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
            $respuesta->subida_archivos = $pregunta->subida_archivos;
            $respuesta->tipo = $pregunta->tipo;
            $respuesta->tipoMarcado = $pregunta->tipo_marcado;
            $respuesta->opciones = AlternativaResource::collection($alternativas);
            $pregunta->opciones = AlternativaResource::collection($alternativas);
            $pregunta->opcionesCorrectas = $pregunta->alternativas()->where('es_correcta', 1)->get()->count();
            $aux = collect(['pregunta' => new PreguntaResource($pregunta), 'respuesta' => new RespuestaResource($respuesta)]);
            $preguntas_respuestas->push($aux);
        }
        return response()->json($preguntas_respuestas, 200);
    }


    public function guardarRespuesta (Request $request){
        $respuesta = Respuesta::find($request->id);
        $respuesta->numero_intento = $respuesta->numero_intento + 1;
        if($request->tipo == 0){
            $respuesta->redaccion = $request->texto;
            $respuesta->estado = 1;
        
            $respuesta->save();
        }
        else{
            $respuesta->alternativas()->detach();
            $opciones = $request->opciones;
            foreach($opciones as $opcion){
                if($opcion['marcado']==1)
                    $respuesta->alternativas()->attach($opcion['id']);
            }
            $pregunta = $respuesta->pregunta()->first();
            $alternativas = $pregunta->alternativas()->get();
            $puntaje_completo = 1;
            foreach($opciones as $opcion){
                foreach($alternativas as $alternativa){
                    if($alternativa->id == $opcion['id']){
                        if($opcion['marcado']!==$alternativa->es_correcta)
                            $puntaje_completo = 0;
                        break;
                    }
                }
                if($puntaje_completo==0) break;
            }
            if($pregunta->tipo_penalizacion==0){
                if($puntaje_completo == 1){
                    $respuesta->puntaje_obtenido = (float) $pregunta->puntaje;
                }
                else{
                    $respuesta->puntaje_obtenido = 0;
                }
                $respuesta->estado = 2;
            
                $respuesta->save();
                return response()->json([
                    "intentos" => $respuesta->numero_intento,
                    "puntajeAsignado" => $respuesta->puntaje_obtenido,
                ]);
            }
            else{
                if($puntaje_completo == 1){
                    $respuesta->puntaje_obtenido = (float) $pregunta->puntaje-(($respuesta->numero_intento-1)*$pregunta->puntaje*(1/($pregunta->cant_intentos)));
                }
                else{
                    $respuesta->puntaje_obtenido = 0;
                }
                $respuesta->estado = 2;
            
                $respuesta->save();
                return response()->json([
                    "intentos" => $respuesta->numero_intento,
                    "puntajeAsignado" => $respuesta->puntaje_obtenido,
                ]);
            }
        }
        
    }

    public function guardarArchivo (Request $request){
        try{
            $respuesta = Respuesta::find($request->idRespuesta);
            $path = $request->file('archivo')->store('ArchivosRespuesta');
            $respuesta->ruta_archivo = $path;
            $respuesta->nombre_archivo = $request->file('archivo')->getClientOriginalName();
            $respuesta->save();
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    public function descargarArchivo ($idRespuesta){
        try{
            $respuesta = Respuesta::find($idRespuesta);
            return Storage::download($respuesta->ruta_archivo,$respuesta->nombre_archivo);
        }
        catch(Exception $e)
        {
            return false;
        }
    }

}
