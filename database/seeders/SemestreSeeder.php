<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tSemestre')->insert([
            ['id' => 1, 'semestre' => '2020-2', 'fecha_inicio' => '2020-08-01 00:00:00', 'fecha_fin' => '2020-12-22 00:00:00', 'fecha_creacion' => now(), 'estado' => 'ACT'],
            ['id' => 2, 'semestre' => '2021-0', 'fecha_inicio' => '2021-01-01 00:00:00', 'fecha_fin' => '2021-04-22 00:00:00', 'fecha_creacion' => now(), 'estado' => 'INA'],
        ]);
    }
}
