<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tUsuario', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 8)->nullable()->default(null);
            $table->string('nombre')->nullable()->default(null);
            $table->string('apellido_paterno')->nullable()->default(null);
            $table->string('apellido_materno')->nullable()->default(null);
            //table->string('mascara1', 16)->nullable()->default(null);
            //$table->string('mascara2', 16)->nullable()->default(null);
            $table->string('email')->unique();
            //$table->timestamp('email_verified_at')->nullable();
            //$table->string('password')->nullable();
            //$table->rememberToken();
            //$table->foreignId('current_team_id')->nullable();
            //$table->text('profile_photo_path')->nullable();
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
        Schema::dropIfExists('tUsuario');
    }
}
