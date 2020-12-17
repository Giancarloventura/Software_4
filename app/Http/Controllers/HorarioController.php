<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgregarParticipanteRequest;
use App\Http\Requests\EditarHorarioRequest;
use App\Http\Requests\EliminarHorarioRequest;
use App\Http\Requests\InsertarHorarioRequest;
use App\Http\Requests\ListarHorarioXCursoXCicloRequest;
use App\Http\Requests\ListarParticipanteXHorarioRequest;
use App\Http\Requests\ObtenerIDHorarioRequest;
use App\Http\Requests\RetirarParticipanteRequest;
use App\Http\Requests\RolUsuarioRequest;
use App\Models\UsuarioRol;
use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Semestre;
use App\Models\Curso;
use App\Models\User;
use App\Imports\HorariosImport;
use App\Imports\CursosImport;
use Maatwebsite\Excel\Facades\Excel;

class HorarioController extends Controller
{
    public function insertarHorario(InsertarHorarioRequest $request)
    {
        $semestre = Semestre::select('tSemestre.id')->where('tSemestre.semestre', "=", $request->codigoSemestre)->get();
        $idSemestre = $semestre[0]->id;

        $curso = Curso::select('tCurso.id')->where('tCurso.codigo', "=",$request->codigoCurso)->get();
        $idCurso = $curso[0]->id;

        if(Horario::where("tHorario.idtSemestre", "=", $idSemestre)
                ->where("tHorario.idtCurso", "=", $idCurso)
                ->where("tHorario.horario", "=", $request->horario)
                ->count() == 0) //Horario no existe
        {
            $horario = new Horario();
            $horario->idtSemestre = $idSemestre;
            $horario->idtCurso = $idCurso;
            $horario->horario = $request->horario;
            $horario->save();

            if(User::where("tUsuario.codigo", $request->codigoProfesor)->count() == 0){ //Profesor no existe
                $usuario = new User();
                $usuario->codigo = $request->codigoProfesor;
                $usuario->email = $request->emailProfesor;
                $usuario->save();

                $profesor = User::select("tUsuario.id")->where("tUsuario.codigo", "=", $request->codigoProfesor)->get();
                $idProfesor = $profesor[0]->id;

                $buscaHorario = Horario::select("tHorario.id")->where("tHorario.idtSemestre", "=", $idSemestre)
                    ->where("tHorario.idtCurso", "=", $idCurso)
                    ->where("tHorario.horario", "=", $request->horario)
                    ->get();
                $idHorario = $buscaHorario[0]->id;

                $participante = new UsuarioRol();
                $participante->idtUsuario = $idProfesor;
                $participante->idtHorario = $idHorario;
                $participante->idtRol = 3;
                $participante->save();
            }
            else{ //Profesor ya existe
                $buscaUsuario = User::select("tUsuario.id")->where("tUsuario.codigo", "=", $request->codigoProfesor)->get();
                $idProfesor = $buscaUsuario[0]->id;

                $buscaHorario = Horario::select("tHorario.id")->where("tHorario.idtSemestre", "=", $idSemestre)
                    ->where("tHorario.idtCurso", "=", $idCurso)
                    ->where("tHorario.horario", "=", $request->horario)
                    ->get();
                $idHorario = $buscaHorario[0]->id;

                $participante = new UsuarioRol();
                $participante->idtUsuario = $idProfesor;
                $participante->idtHorario = $idHorario;
                $participante->idtRol = 3;
                $participante->save();
            }
            return response()->json("Horario y profesor insertado correctamente", 200);
        }
        else{ //Horario ya existe
            return response()->json("El horario ya se encuentra en el sistema", 200);
        }
    }

    public function editarHorario(EditarHorarioRequest $request)
    {
        if(User::where("tUsuario.codigo", $request->codigoProfesor)->count() == 0){ //Usuario no existe
            $usuario = new User();
            $usuario->codigo = $request->codigoProfesor;
            $usuario->email = $request->emailProfesor;
            $usuario->save();
        }

        $profesor = User::select("tUsuario.id")->where("tUsuario.codigo", "=", $request->codigoProfesor)->get();
        $idProfesor = $profesor[0]->id;

        $editar = UsuarioRol::where('tUsuario_tRol.idtHorario', '=', $request->idHorario)
            ->where("tUsuario_tRol.idtRol", "=", 3)
            ->first();
        $editar->idtUsuario = $idProfesor;
        $editar->save();

        return response()->json('Horario editado correctamente', 200);
    }

    public function eliminarHorario(EliminarHorarioRequest $request)
    {
        $horario = Horario::findOrFail($request->idHorario);
        $horario->estado = 'INA';
        $horario->save();

        return response()->json("Horario eliminado correctamente", 200);
    }

    public function detalleHorario($id)
    {
        $horario = Horario::with(['curso', 'semestre', 'evaluaciones'])->find($id);

        return response()->json($horario, 200);
    }

    public function listarParticipantes($id)
    {
        $participantes = Horario::find($id)->usuarios_roles()->with(['usuario', 'rol'])->get();

        return response()->json($participantes, 200);
    }

    public function listarParticipantesSinAsignar($id)
    {
        $participantes = User::whereDoesntHave('horarios', function($query) use ($id) {
            $query->where('tHorario.id', $id);
        })->get();

        return response()->json($participantes, 200);
    }

    //Elimina completamente el registro, porque no hay un campo estado
    public function retirarParticipante(RetirarParticipanteRequest $request)
    {
        $usuario = UsuarioRol::select("tUsuario_tRol.id")->where("tUsuario_tRol.idtUsuario", $request->idUsuario)
            ->where("tUsuario_tRol.idtHorario", $request->horario)->get();
        $id = $usuario[0]->id;

        $retirar = UsuarioRol::destroy($id);

        return response()->json("Participante retirado", 200);
    }

    public function agregarParticipante(AgregarParticipanteRequest $request)
    {
        if(User::where("tUsuario.codigo", $request->codigoUsuario)->count() == 0){ //Usuario no existe
            $usuario = new User();
            $usuario->codigo = $request->codigoUsuario;
            $usuario->email = $request->email;

            $usuario->save();

            $usuario = User::select("tUsuario.id")->where("tUsuario.codigo", "=", $request->codigoUsuario)->get();
            $id = $usuario[0]->id;

            if (UsuarioRol::where('tUsuario_tRol.idtUsuario', $id)->where('tUsuario_tRol.idtHorario', $request->horario)->count() == 0) { // Usuario no asignado

                $participante = new UsuarioRol();
                $participante->idtUsuario = $id;
                $participante->idtHorario = $request->horario;
                $participante->idtRol = $request->rol;

                $participante->save();

                return response()->json('Usuario creado y asignado correctamente', 200);
            }
        }
        else{ //Usuario ya existe
            $usuario = User::select("tUsuario.id")->where("tUsuario.codigo", "=", $request->codigoUsuario)->get();
            $id = $usuario[0]->id;

            if (UsuarioRol::where('tUsuario_tRol.idtUsuario', $id)->where('tUsuario_tRol.idtHorario', $request->horario)->count() == 0) { // Usuario no asignado

                $participante = new UsuarioRol();
                $participante->idtUsuario = $id;
                $participante->idtHorario = $request->horario;
                $participante->idtRol = $request->rol;

                $participante->save();

                return response()->json('Usuario asignado correctamente', 200);
            } else { // Usuario ya se encuentra asignado en el horario
                return response()->json("El participante ya se encuentra asignado en el horario", 200);
            }
        }
    }

    public function subirCSVParticipantes($id, Request $request)
    {
        $archivos = $request->file('archivo');
        //$archivo = $archivo->openFile();
        $archivo = fopen($archivos, 'r');
        $linea = 0;
        $duplicados = 0;
        $cantidadParticipantes = 0;

        while (!feof($archivo)) {
            $linea++;
            $lin = fgets($archivo);
            $fila = explode(";", $lin);

            if ($linea >= 8) {
                $cantidadParticipantes += 1;
                $codigo = $fila[0];
                //$nombre = explode(",", $fila[1])[1];
                //$nombre = ltrim($nombre, $nombre[0]);
                //$apellido = explode(",", $fila[1])[0];
                //$array = array($nombre, $apellido);
                //$nombre_completo = implode(" ", $array);
                $cantidad_email = substr_count($fila[4], ',');
                if($cantidad_email == 1){
                    $email = explode(",", $fila[4])[0];
                }
                else{
                    $email = $fila[4];
                }


                $rol_id = null;
                $fila[5] = str_replace("\r\n", "", $fila[5]);

                switch ($fila[5]) {
                    case 1: // Alumno
                        $rol_id = 5;
                    break;

                    case 2: // Jefe de Laboratorio
                        $rol_id = 4;
                    break;

                    case 3: // Profesor
                        $rol_id = 3;
                    break;

                    default: // Alumno
                        $rol_id = 5;
                    break;
                }

                if (User::where('email', $email)->count() == 0) { // no existe
                    $usuario = new User;

                    $usuario->codigo = $codigo;
                    //$usuario->nombre = $nombre_completo;
                    $usuario->email = $email;
                    $usuario->password = '';
                    $usuario->save();
                } else { // existe
                    $usuario = User::where('email', $email)->first();
                }

                $busqueda = User::select("tUsuario.id")->where("tUsuario.codigo", "=", $codigo)->get();
                $idUsuario = $busqueda[0]->id;

                if (UsuarioRol::where('tUsuario_tRol.idtUsuario', $idUsuario)->where('tUsuario_tRol.idtHorario', $id)->count() == 0) { // Usuario no asignado

                    $participante = new UsuarioRol();
                    $participante->idtUsuario = $idUsuario;
                    $participante->idtHorario = $id;
                    $participante->idtRol = $rol_id;

                    $participante->save();
                } else { // Usuario ya se encuentra asignado en el horario
                    $duplicados = $duplicados + 1;
                }
            }
        }
        fclose($archivo);
        //return response()->json($linea - 7, 200);
        return response()->json("La asignacion se realizo correctamente con $duplicados duplicados de $cantidadParticipantes participantes", 200);
    }

    public function importarHorariosVistaPrevia(Request $request){
        //$data = Excel::toCollection(new CursosImport(2), $request->file('file'));
        $data = (new CursosImport(0))->toCollection($request->file('file'))[0];

        return response($data,200);
    }

    public function importarHorarios(Request $request){
        //$data = Excel::toCollection(new CursosImport(2), $request->file('file'));
        $import = new HorariosImport($request->idtSemestre, $request->codCurso);
        $import->import($request->file('file'));

        //Excel::import(new HorariosImport($request->idtSemestre, $request->codCurso), $request->file('file'));
        //dd($import->errors());
        return response()->json([
            'errores' => $import->errores(),
        ],200);
    }

    public function listarParticipantesXHorario(ListarParticipanteXHorarioRequest $request)
    {
        $horario = Horario::join('tUsuario_tRol', 'tUsuario_tRol.idtHorario', '=', 'tHorario.id')
            ->join('tUsuario', 'tUsuario.id', '=', 'tUsuario_tRol.idtUsuario')
            ->join('tRol', 'tRol.id', '=', 'tUsuario_tRol.idtRol')
            ->join('tCurso', 'tCurso.id', '=', 'tHorario.idtCurso')
            ->join('tSemestre', 'tSemestre.id', '=', 'tHorario.idtSemestre')
            ->select('tUsuario.id', 'tUsuario.codigo', 'tUsuario.nombre', 'tUsuario.apellido_paterno', 'tUsuario.apellido_materno',
                'tUsuario.email', 'tRol.nombre as rol')
            ->where('tSemestre.semestre', '=', $request->semestre)
            ->where('tCurso.codigo', '=', $request->codigoCurso)
            ->where('tHorario.horario', '=', $request->horario)
            ->orderBy('tUsuario.codigo')
            ->get();

        return response()->json($horario, 200);
    }

    public function obtenerIDHorario(ObtenerIDHorarioRequest $request)
    {
        $horario = Horario::join('tCurso', 'tCurso.id', '=', 'tHorario.idtCurso')
            ->join('tSemestre', 'tSemestre.id', '=', 'tHorario.idtSemestre')
            ->select('tHorario.id')
            ->where('tHorario.horario', '=', $request->horario)
            ->where('tSemestre.semestre', '=', $request->semestre)
            ->where('tCurso.codigo', '=', $request->codigoCurso)
            ->get();

        return response()->json($horario, 200);
    }

    public function listarHorarioXCursoXCiclo(ListarHorarioXCursoXCicloRequest $request)
    {
        $horarios = Horario::join('tCurso', 'tCurso.id', '=', 'tHorario.idtCurso')
            ->select('tHorario.id', 'tHorario.horario')
            ->where('tHorario.idtSemestre', '=', $request->idSemestre)
            ->where('tHorario.idtCurso', '=', $request->idCurso)
            ->where('tHorario.estado', '=', 'ACT')
            ->where('tCurso.estado', '=', 'ACT')
            ->get();

        return response()->json($horarios, 200);
    }

    public function rolUsuario(RolUsuarioRequest $request)
    {
        $rol = UsuarioRol::select('tUsuario_tRol.idtRol')
            ->where('tUsuario_tRol.idtUsuario', '=', $request->idUsuario)
            ->where('tUsuario_tRol.idtHorario', '=', $request->idHorario)
            ->first();

        $idRol = $rol->idtRol;

        return response()->json($idRol, 200);
    }
}
