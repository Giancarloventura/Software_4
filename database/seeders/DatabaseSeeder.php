<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(SemestreSeeder::class);
        $this->call(UnidadAcademicaSeeder::class);
        $this->call(CursoSeeder::class);
        $this->call(HorarioSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(UsuarioSeeder::class);
    }
}
