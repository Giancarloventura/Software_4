<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\UnidadAcademica;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CursoResource;

class UnidadAcademicaController extends Controller
{
    // Crea una nueva Unidad Académica con estado ACTIVO
    public function insertar(Request $request)
    {
        try
        {
            $usuario = User::select('id')->where('codigo', $request->Usuario_codigo)->first();

            if(is_null($usuario))
            {
                $usuario = new User();
                $usuario->email = $request->Usuario_email;
                $usuario->codigo = $request->Usuario_codigo;

                $usuario->save();
            }

            $unidad_academica = new UnidadAcademica();
            $unidad_academica->nombre = $request->UnidadAcademica_nombre;
            $unidad_academica->codigo = $request->UnidadAcademica_codigo;
            $unidad_academica->estado = 'ACT';
            $unidad_academica->tusuario_id = $usuario->id;
            $unidad_academica->tusuario_id_creacion = $request->tusuario_id_creacion;
            $unidad_academica->fecha_actualizacion = NULL;
            $unidad_academica->save();
            return response()->json(['status' => 'success'], 201);
        }
        catch (Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '\n';
        }
    }

    // Actualiza una Unidad Académica
    public function actualizar(Request $request)
    {
        try
        {
            $usuario = User::select('id')->where('codigo', $request->Usuario_codigo)->first();

            if(is_null($usuario))
            {
                $usuario = new User();
                $usuario->email = $request->Usuario_email;
                $usuario->codigo = $request->Usuario_codigo;

                $usuario->save();
            }

            $usuario->email = $request->Usuario_email;
            $usuario->save();

            $unidad_academica = UnidadAcademica::findOrFail($request->UnidadAcademica_id);
            $unidad_academica->nombre = $request->UnidadAcademica_nombre;
            $unidad_academica->codigo = $request->UnidadAcademica_codigo;
            $unidad_academica->estado = 'ACT';
            $unidad_academica->tusuario_id = $usuario->id;
            $unidad_academica->tusuario_id_actualizacion = $request->tusuario_id_actualizacion;
            $unidad_academica->save();
            return response()->json(['status' => 'success'], 200);
        }
        catch(Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '\n';
        }
    }

    // Cambia el estado de una Unidad Académica a ELIMINADO (solo es necesario el ID)
    public function eliminar(Request $request)
    {
        try
        {
            $unidad_academica = UnidadAcademica::findOrFail($request->id);
            $unidad_academica->estado = 'ELI';
            $unidad_academica->tusuario_id_actualizacion = $request->tusuario_id_actualizacion;
            $unidad_academica->save();
            return response()->json(['status' => 'success'], 200);
        }
        catch(Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '\n';
        }
    }

    // Hace un DELETE de la Unidad Académica (solo es necesario el ID)
    public function depurar(Request $request)
    {
        try
        {
            $unidad_academica = UnidadAcademica::findOrFail($request->id);
            $unidad_academica->delete();
            return response()->json(['status' => 'success'], 200);
        }
        catch(Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '\n';
        }
    }

    // Lista todas las unidades académicas que se encuentren con estado ACTIVO
    public function listar()
    {
        try
        {
            $datos["unidad_academica"] = UnidadAcademica::select('a.id as UnidadAcademica_id',
                                        'a.codigo as UnidadAcademica_codigo',
                                        'a.nombre as UnidadAcademica_nombre',
                                        'u.id as Usuario_id',
                                        'u.codigo as Usuario_codigo',
                                        'u.email as Usuario_email')
                                        ->from('tUnidadAcademica as a')
                                        ->join('tUsuario as u', 'a.tusuario_id', '=', 'u.id')
                                        ->where('a.estado','ACT')
                                        ->orderBy('a.codigo','ASC')->get();
            if (is_null($datos["unidad_academica"]))
            {
                return response()->json([],204);
            }
            else
            {
                return response()->json($datos["unidad_academica"], 200);
            }
        }
        catch(Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '\n';
        }
    }

    public function import_form()
    {
        return view('import-form');
    }

    public function importarUnidadesAcademicas(Request $request)
    {
        try
        {
            $archivo = $request->file('archivo');
            $archivo = $archivo->openFile();
            $flag = True;
            while(! $archivo->eof())
            {
                $fila = explode(";", $archivo->fgets());
                if($flag)
                {
                    $flag = False;
                    continue;
                }
                $object_json = array(
                    'UnidadAcademica_codigo'    => $fila[0],
                    'UnidadAcademica_nombre'    => $fila[1],
                    'Usuario_codigo'            => $fila[2],
                    'Usuario_email'             => str_replace("\r\n", "", $fila[3])
                );
                $req = new Request($object_json);
                $this->insertar($req);
            }
            return response()->json(['status' => 'success'], 201);
        }
        catch (Exception $e)
        {
            echo 'Excepción capturada: ' . $e->getMessage() . '||\n';
        }
    }

    public function listarCursos(Request $request)
    {
        $ua = UnidadAcademica::find($request->idUnidadAcademica);
        return response()->json(CursoResource::collection($ua->cursos()->where('estado', "ACT")->get(),200));
    }
}
