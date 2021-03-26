<?php

namespace Tests\Feature;

use App\Models\Doctor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    // use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->withoutExceptionHandling();
        // $doctor = Doctor::create([
        //     'name' => "cairo",
        //     'email' => "22",
        //     'password' => "55",
        //     'phone' => "100",
        //     'spatial' => "this name",
        //     'is_first_come' => true,
        // ]);
        $response = $this->patch('/api/test/22', [
            'is_first_come' => true,
            'duration_min' => 40,

            'tableList' => [
                [
                    'day'   => 'sun',
                    'is_avail' => true,
                    'start_time' => '33:00',
                    'end_time' => '12:00',
                    'count_att' => 20
                ],

                [
                    'day' => 'wen',
                    'is_avail' => true,
                    'start_time' => '03:00',
                    'end_time' => '12:00',
                    'count_att' => 50
                ],

                [
                    'day' => 'tus',
                    'is_avail' => true,
                    'start_time' => '03:00',
                    'end_time' => '12:00',
                    'count_att' => 20
                ]
            ]
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function showAll() {
        $this->withoutExceptionHandling();

        $response = $this->get('api/test2/22ddf');
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function changeState() {
        $this->withoutExceptionHandling();


        $response = $this->patch('api/test3/22ddf', [
            'id'=> 1,
            'state' => -1
        ]);
        $response->assertStatus(200);
    }
}
