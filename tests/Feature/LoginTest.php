<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class LoginTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;
    /** @test */
    public function it_visit_page_of_login()
    {

        $user = User::create([
            "email" => "a20171703@pucp.edu.pe",
            "codigo" =>"20159834",
            "nombre" =>"pruebaTest",
        ]);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json'
        ])->json('POST','/api/login', ['email' => 'a20171703@pucp.edu.pe']);

        $response
            ->assertStatus(200)
            ->assertJson([
                "codigo" => "20159834",
                "email" => "a20171703@pucp.edu.pe",
                "nombre" => "pruebaTest",
                "esAdmin" => 0,
                "esAlumno" => 0,
                "esCoordinador" => 0,
                "esProfesor" => 0,
                "esJL" => 0
            ]);
    }
}
