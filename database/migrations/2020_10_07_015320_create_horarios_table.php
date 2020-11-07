<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tHorario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idtSemestre')->constrained('tSemestre');
            $table->foreignId('idtCurso')->constrained('tCurso');
            $table->string('horario', 10);
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');
            $table->string('estado', 3)->default('ACT');
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
        Schema::dropIfExists('tHorario');
    }
}
