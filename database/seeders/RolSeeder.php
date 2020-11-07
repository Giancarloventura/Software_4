<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tRol')->insert([
            ['id' => 1, 'nombre' => 'Admin', 'fecha_creacion' => now()],
            ['id' => 2, 'nombre' => 'Coordinador', 'fecha_creacion' => now()],
            ['id' => 3, 'nombre' => 'Profesor', 'fecha_creacion' => now()],
            ['id' => 4, 'nombre' => 'Jefe de Laboratorio', 'fecha_creacion' => now()],
            ['id' => 5, 'nombre' => 'Alumno', 'fecha_creacion' => now()],
        ]);
    }
}
