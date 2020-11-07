<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class HorarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tHorario')->insert([
            ['id' => 1, 'idtSemestre' => 1, 'idtCurso' => 1, 'horario' => '8881', 'fecha_inicio' => '2020-11-01 00:00:00', 'fecha_fin' => '2020-12-22 00:00:00'],
            ['id' => 2, 'idtSemestre' => 1, 'idtCurso' => 1, 'horario' => '8882', 'fecha_inicio' => '2020-11-01 00:00:00', 'fecha_fin' => '2020-12-22 00:00:00'],
            ['id' => 3, 'idtSemestre' => 1, 'idtCurso' => 2, 'horario' => '8883', 'fecha_inicio' => '2020-11-01 00:00:00', 'fecha_fin' => '2020-12-22 00:00:00'],
        ]);
    }
}
