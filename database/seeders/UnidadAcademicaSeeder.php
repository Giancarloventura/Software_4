<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UnidadAcademicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tUnidadAcademica')->insert([
            ['id' => 1, 'nombre' => 'FACI', 'codigo' => 'FACI', 'fecha_creacion' => now()],
        ]);
    }
}
