<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tEvaluacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idtHorario')->constrained('tHorario');
            $table->string('nombre', 100);
            $table->decimal('puntaje_obtenido', 6, 2)->nullable()->default(null);
            $table->decimal('puntaje', 6, 2)->nullable()->default(null);
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
        Schema::dropIfExists('tEvaluacion');
    }
}
