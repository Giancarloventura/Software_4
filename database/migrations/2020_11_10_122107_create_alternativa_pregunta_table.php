<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlternativaPreguntaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tAlternativa_Pregunta', function (Blueprint $table) {
            $table->id();
            $table->string('enunciado', 1000);
            $table->string('ruta_archivo', 500);
            $table->tinyInteger('es_imagen');
            $table->tinyInteger('es_correcta');
            $table->timestamp('fecha_creacion')->nullable();
            $table->timestamp('fecha_actualizacion')->nullable();
            $table->foreignId('idtPregunta')->constrained('tPregunta');
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
        Schema::dropIfExists('tAlternativa_Pregunta');
    }
}
