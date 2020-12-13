<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\SemestreController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\FaseController;
use App\Http\Controllers\UnidadAcademicaController;
use App\Http\Controllers\RespuestaController;
use App\Http\Controllers\ComentarioControlador;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\AlternativaPreguntaController;
use \App\Http\Controllers\MailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// RUTAS PARA LAS EVALUACIONES
Route::post('crear-evaluacion', [EvaluacionController::class, 'crearEvaluacion']);
Route::get('evaluaciones', [EvaluacionController::class, 'listarEvaluaciones']);
Route::post('evaluacion-codigo', [EvaluacionController::class, 'obtenerEvaluacionXCodigo']);
Route::post('obtener-fases-de-evaluacion', [EvaluacionController::class, 'obtenerFasesXEvaluacion']);
Route::post('copiar-evaluacion', [EvaluacionController::class, 'copiarEvaluacion']);
Route::post('dashboard-evaluacion', [EvaluacionController::class, 'dashboardEvaluacion']);
Route::post('eliminar-evaluacion', [EvaluacionController::class, 'eliminarEvaluacion']);
Route::post('editar-evaluacion', [EvaluacionController::class, 'editarEvaluacion']);
Route::post('listar-notas-evaluaciones', [EvaluacionController::class, 'listarNotasEvaluaciones']);
Route::post('piechart-aprobados', [EvaluacionController::class, 'pieChartAprobados']);
Route::post('distribucion-notas', [EvaluacionController::class, 'distribucionNotas']);

// RUTAS PARA LOS CURSOS
Route::get('cursos-actuales', [CursoController::class, 'listarCursosActuales']);
Route::post('listarLaboratoriosPorHorario', [CursoController::class, 'listarLaboratoriosPorHorario']);
Route::post('insertar-curso', [CursoController::class, 'insertarCurso']);
Route::post('editar-curso', [CursoController::class, 'editarCurso']);
Route::post('eliminar-curso', [CursoController::class, 'eliminarCurso']);

// RUTAS PARA LOS SEMESTRES
Route::get('semestre-actual', [SemestreController::class, 'semestreActual']);
Route::get('listar-semestres',[SemestreController::class, 'listarSemestres']);
Route::post('crear-semestres',[SemestreController::class, 'crearSemestre']);
Route::post('editar-semestres',[SemestreController::class, 'editarSemestre']);
Route::post('eliminar-semestre',[SemestreController::class, 'eliminarSemestre']);
Route::post('obtener-semestre',[SemestreController::class, 'obtenerSemestreXCodigo']);

// RUTAS PARA LOS HORARIOS
Route::post('insertar-horario', [HorarioController::class, 'insertarHorario']);
Route::post('editar-horario', [HorarioController::class, 'editarHorario']);
Route::post('eliminar-horario', [HorarioController::class, 'eliminarHorario']);
Route::get('detalle-horario/{id}', [HorarioController::class, 'detalleHorario']);
Route::get('listar-participantes/{id}', [HorarioController::class, 'listarParticipantes']);
Route::get('listar-participantes-sin-asignar/{id}', [HorarioController::class, 'listarParticipantesSinAsignar']);
Route::post('retirar-participante', [HorarioController::class, 'retirarParticipante']);
Route::post('agregar-participante', [HorarioController::class, 'agregarParticipante']);
Route::post('subir-csv-participantes/{id}', [HorarioController::class, 'subirCSVParticipantes']);
Route::post('rol-usuario-horario', [HorarioController::class, 'rolUsuario']);

// RUTAS PARA LOS USUARIOS
Route::post('crear-participante', [UsuarioController::class, 'crearParticipante']);

Route::post('suspender-user', [UsuarioController::class, 'suspenderUsuario']);
Route::post('activar-user', [UsuarioController::class, 'activarUsuario']);
Route::get('listar-usuarios', [UsuarioController::class, 'listarUsuarios']);
Route::post('historico-cursos-alumno', [UsuarioController::class, 'listarHistoricoCursosAlumno']);
Route::post('historico-cursos-profesorjl', [UsuarioController::class, 'listarHistoricoCursosProfesorJL']);
Route::post('obtener-informacion-usuario', [UsuarioController::class, 'obtenerUsuarioporID']);

// RUTAS PARA LAS FASES
Route::post('crear-fase', [FaseController::class, 'crearFase']);
Route::post('editar-fase', [FaseController::class, 'editarFase']);
Route::post('eliminar-fase', [FaseController::class, 'eliminarFase']);
Route::get('listar-fases/{id}', [FaseController::class, 'listarFases']);
Route::post('obtener-fase', [FaseController::class, 'obtenerFase']);
Route::post('cantidad-preguntas-fase', [FaseController::class, 'obtenerCantidadPreguntas']);
Route::post('crear-comentario-fase', [FaseController::class, 'crearComentario']);
Route::post('listar-comentario-fase', [FaseController::class, 'listarComentarioXAlumno']);
Route::post('crear-preguntas-aleatorias', [FaseController::class, 'crearPreguntasAleatorias']);
Route::post('dashboard-fase', [FaseController::class, 'dashboardFase']);
Route::post('publicar-notas', [FaseController::class, 'setNotasPublicadas']);
Route::post('editar-descripcion-fase', [FaseController::class, 'editarDescripcionFase']);

// RUTAS PARA LAS UNIDADES ACADÉMICAS
Route::group(['prefix' => 'unidadesacademicas'], function () {
    Route::put('insertar', [\App\Http\Controllers\UnidadAcademicaController::class, 'insertar']);
    Route::post('actualizar', [\App\Http\Controllers\UnidadAcademicaController::class, 'actualizar']);
    Route::post('eliminar', [\App\Http\Controllers\UnidadAcademicaController::class, 'eliminar']);
    Route::post('depurar', [\App\Http\Controllers\UnidadAcademicaController::class, 'depurar']);
    Route::get('listar', [\App\Http\Controllers\UnidadAcademicaController::class, 'listar']);
    Route::post('importar', [\App\Http\Controllers\UnidadAcademicaController::class, 'importarUnidadesAcademicas']);
});


Route::get('listar-horarios', [UsuarioController::class, 'listarHorarios']);
Route::post('listar-horario-curso-ciclo', [HorarioController::class, 'listarHorarioXCursoXCiclo']);

Route::post('listar-horarios-de-usuario', [UsuarioController::class, 'listarHorarios']);
Route::post('login', [UsuarioController::class, 'login']);

Route::post('subir-csv-cursos-vista-previa', [CursoController::class, 'importarCursosVistaPrevia']);
Route::post('subir-csv-cursos', [CursoController::class, 'importarCursos']);
Route::post('subir-csv-horarios-vista-previa', [HorarioController::class, 'importarHorariosVistaPrevia']);

Route::post('subir-csv-horarios', [HorarioController::class, 'importarHorarios']);

// RUTAS PARA LAS PREGUNTAS
Route::post('agregar-pregunta', [PreguntaController::class, 'agregarPregunta']);
Route::post('editar-pregunta', [PreguntaController::class, 'editarPregunta']);
Route::post('eliminar-pregunta', [PreguntaController::class, 'eliminarPregunta']);
Route::post('intercambiar-orden', [PreguntaController::class, 'intercambiarOrden']);

Route::post('agregar-pregunta-x-fase', [FaseController::class, 'agregarPreguntaXFase']);
Route::post('agregar-alternativa-pregunta', [AlternativaPreguntaController::class, 'agregarAlternativa']);

Route::post('cursos-usuario', [CursoController::class, 'listarCursosXUsuario']);
Route::post('evaluaciones-horario', [EvaluacionController::class, 'listarEvaluacionesXHorario']);
Route::post('fases-evaluacion', [FaseController::class, 'listarFasesXEvaluacion']);
Route::post('participantes-horario', [HorarioController::class, 'listarParticipantesXHorario']);
Route::post('curso-codigo', [CursoController::class, 'obtenerCursoXCodigo']);
Route::post('obtener-id-horario', [HorarioController::class, 'obtenerIDHorario']);

Route::post('obtener-email', [UsuarioController::class, 'getEmail']);
Route::post('listar-horarios-de-curso', [CursoController::class, 'listarHorarios']);
Route::post('listar-cursos-de-unidad-academica', [UnidadAcademicaController::class, 'listarCursos']);
Route::post('obtener-unidad-academica', [UsuarioController::class, 'getUnidaAcademica']);
Route::post('obtener-semestres-de-usuario', [UsuarioController::class, 'getSemestres']);

Route::post('modificar-nota-comentario-alumno', [RespuestaController::class, 'modificarNotaComentarioAlumno']);

Route::post('listar-comentario-fase-alumno', [ComentarioControlador::class, 'listarComentariosPorFaseAlumno']);

Route::post('listar-preguntas-de-alumno', [RespuestaController::class, 'listarPreguntasdeAlumno']);
Route::post('listar-preguntas-de-alumno-aleatorias', [RespuestaController::class, 'listarPreguntasAleatoriasdeAlumno']);
Route::post('listar-preguntas-de-profesor', [PreguntaController::class, 'listarPreguntasdeProfesor']);
Route::post('guardar-respuesta', [RespuestaController::class, 'guardarRespuesta']);

Route::get('fases/{id}/seguimiento', [FaseController::class,'getSeguimiento']);


Route::post('comentario-alumno', [RespuestaController::class, 'agregarComentarioAlumno']);

Route::get('fases/{id}/seguimiento', [FaseController::class,'getSeguimiento']);




Route::post('listar-preguntas-de-alumno', [RespuestaController::class, 'listarPreguntasdeAlumno']);

Route::get('fases/{id}/seguimiento', [FaseController::class,'getSeguimiento']);

Route::post('resumen-notas-alumno', [EvaluacionController::class, 'resumenNotasAlumno']);

Route::post('enviar-mail-prueba', [MailController::class, 'correccionPrueba']);
Route::post('guardar-archivo', [RespuestaController::class, 'guardarArchivo']);
Route::post('descargar-archivo', [RespuestaController::class, 'descargarArchivo']);
