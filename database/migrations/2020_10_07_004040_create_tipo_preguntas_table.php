<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoPreguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tTipo_Pregunta', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
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
        Schema::dropIfExists('tTipo_Pregunta');
    }
}
