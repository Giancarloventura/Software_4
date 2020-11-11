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

Route::post('crear-evaluacion', [EvaluacionController::class, 'crearEvaluacion']);
Route::get('evaluaciones', [EvaluacionController::class, 'listarEvaluaciones']);
Route::post('evaluacion-codigo', [EvaluacionController::class, 'obtenerEvaluacionXCodigo']);
Route::post('obtener-fases-de-evaluacion', [EvaluacionController::class, 'obtenerFasesXEvaluacion']);
Route::post('copiar-evaluacion', [EvaluacionController::class, 'copiarEvaluacion']);

Route::get('cursos-actuales', [CursoController::class, 'listarCursosActuales']);
Route::post('listarLaboratoriosPorHorario', [CursoController::class, 'listarLaboratoriosPorHorario']);
Route::post('insertar-curso', [CursoController::class, 'insertarCurso']);
Route::post('editar-curso', [CursoController::class, 'editarCurso']);
Route::post('eliminar-curso', [CursoController::class, 'eliminarCurso']);

Route::get('semestre-actual', [SemestreController::class, 'semestreActual']);
Route::get('listar-semestres',[SemestreController::class, 'listarSemestres']);
Route::post('crear-semestres',[SemestreController::class, 'crearSemestre']);
Route::post('editar-semestres',[SemestreController::class, 'editarSemestre']);
Route::post('eliminar-semestre',[SemestreController::class, 'eliminarSemestre']);

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

Route::post('crear-participante', [UsuarioController::class, 'crearParticipante']);

Route::post('crear-fase', [FaseController::class, 'crearFase']);
Route::post('editar-fase', [FaseController::class, 'editarFase']);
Route::post('eliminar-fase', [FaseController::class, 'eliminarFase']);
Route::get('listar-fases/{id}', [FaseController::class, 'listarFases']);
Route::post('obtener-fase', [FaseController::class, 'obtenerFase']);
Route::post('cantidad-preguntas-fase', [FaseController::class, 'obtenerCantidadPreguntas']);

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

Route::post('agregar-pregunta', [PreguntaController::class, 'agregarPregunta']);
Route::post('editar-pregunta', [PreguntaController::class, 'editarPregunta']);

Route::post('agregar-alternativa-pregunta', [AlternativaPregunta::class, 'agregarAlternativa']);

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

Route::get('fases/{id}/seguimiento', [FaseController::class,'getSeguimiento']);


Route::post('comentario-alumno', [RespuestaController::class, 'agregarComentarioAlumno']);
Route::post('listar-preguntas-de-alumno', [RespuestaController::class, 'listarPreguntasdeAlumno']);

Route::get('fases/{id}/seguimiento', [FaseController::class,'getSeguimiento']);



Route::post('listar-comentario-fase-alumno', [ComentarioControlador::class, 'listarComentariosPorFaseAlumno']);

Route::post('listar-preguntas-de-alumno', [RespuestaController::class, 'listarPreguntasdeAlumno']);

Route::get('fases/{id}/seguimiento', [FaseController::class,'getSeguimiento']);

