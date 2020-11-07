<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tFase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idtEvaluacion')->constrained('tEvaluacion');
            $table->string('nombre');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->decimal('puntaje_obtenido', 6, 2)->nullable()->default(null);
            $table->decimal('puntaje', 6, 2)->nullable()->default(null);
            $table->boolean('sincrona')->default(0);
            $table->boolean('preguntas_aleatorias')->default(0);
            $table->integer('preguntas_mostradas');
            $table->integer('disposicion_preguntas')->default(0);
            $table->boolean('permitir_retroceso');
            $table->timestamp('fecha_creacion')->nullable();
            $table->timestamp('fecha_actualizacion')->nullable();
            $table->foreignId('tusuario_id_creacion')->nullable()->constrained('tUsuario');
            $table->foreignId('tusuario_id_actualizacion')->nullable()->constrained('tUsuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fases');
    }
}
