<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function tests_example()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('api/user/auth/register', [
            'name' => 'john',
            'email' => 'john@dd.com',
            'password' => 'johnddd',
            'password_confirmation' => 'johnddd',
            'phone' => '0111111',
        ], [
            'accept' => 'application/json'
        ]);



        $response->assertStatus(200);
    }


    /**
     * A basic feature test example.
     *
     * @test
     */
    public function doctor_register()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('api/doctor/auth/register', [
            'name' => 'john',
            'email' => 'johddn@dd.com',
            'password' => 'johnddd',
            'password_confirmation' => 'johnddd',
            'phone' => '0111111',
            'spatial' => 'cs',
        ], [
            'accept' => 'application/json'
        ]);



        $response->assertStatus(200);
    }

     /**
     *@test
     */
    public function login()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('api/user/auth/login', [
            'email' => 'john@dd.com',
            'password' => 'johnddd',
        ], [
            'accept' => 'application/json'
        ]);



        $response->assertStatus(200);
    }
}
