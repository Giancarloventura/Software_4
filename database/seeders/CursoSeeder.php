<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tCurso')->insert([
            ['id' => 1, 'nombre' => 'Curso 1', 'codigo' => 'CUR001', 'idtUnidadAcademica' => 1, 'fecha_creacion' => now()],
            ['id' => 2, 'nombre' => 'Curso 2', 'codigo' => 'CUR002', 'idtUnidadAcademica' => 1, 'fecha_creacion' => now()],
        ]);
    }
}
