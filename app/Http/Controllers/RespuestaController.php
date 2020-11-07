<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModificarNotaAlumnoRequest;
use App\Models\Respuesta;

class RespuestaController extends Controller
{
    public function modificarNotaAlumno(ModificarNotaAlumnoRequest $request)
    {
        $respuesta = Respuesta::findOrFail($request->idRespuesta);
        $respuesta->puntaje_obtenido = $request->puntaje;
        $respuesta->comentario = $request->comentario;
        $respuesta->save();

        return response()->json("Nota modificada correctamente", 200);
    }
}
