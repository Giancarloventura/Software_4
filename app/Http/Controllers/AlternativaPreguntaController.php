<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AlternativaPregunta;
use Illuminate\Http\Request;

class AlternativaPreguntaController extends Controller
{
   //
    public function agregarAlternativa(Request $request){
        try{

            $usuario = User::select('id')->where('codigo', $request->codigo)->first();

            if(is_null($usuario))
            {
                $usuario = new User();
                $usuario->email = $request->email;
                $usuario->codigo = $request->codigo;

                $usuario->save();
            }

            $alternativa = new AlternativaPregunta();
            $alternativa->id = $request->id;
            $alternativa->enunciado = $request->enunciado;
            $alternativa->ruta_archivo = $request->ruta_archivo;
            $alternativa->es_imagen = $request->es_imagen;
            $alternativa->es_correcta = $request->es_correcta;
            $alternativa->idtPregunta = $request->idtPregunta;
            $alternativa->tusuario_id_creacion = $usuario->id;
            $alternativa->tusuario_id_creacion = $request->tusuario_id_creacion;
            $alternativa->fecha_actualizacion = NULL;
            $alternativa->save();
            return response()->json($alternativa);
        }
        catch(Exception $exception)
        {
            echo 'Excepción capturada: ' . $exception->getMessage() . '\n';
        }
    }
}
