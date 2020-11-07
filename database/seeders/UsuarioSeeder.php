<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tUsuario')->insert([
            ['id' => 1, 'codigo' => '20200410', 'email' => 'test1@test.com', 'password' => ''],
            ['id' => 2, 'codigo' => '20200411', 'email' => 'test2@test.com', 'password' => ''],
            ['id' => 3, 'codigo' => '20200412', 'email' => 'test3@test.com', 'password' => ''],
            ['id' => 4, 'codigo' => '20200413', 'email' => 'test4@test.com', 'password' => ''],
            ['id' => 5, 'codigo' => '20200414', 'email' => 'test5@test.com', 'password' => ''],
        ]);
    }
}
