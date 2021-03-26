<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class DoctorControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @test
     */
    public function doctor_reservation()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::find('john@dd.com'));
        $response = $this->post('api/patient/reserve', [
            'doctor_email' => 'johdn@dd.com',
            'date' => '18/03/2021',
            'day' => 'sun',
            "interval" => '05:30',
            'state' => 0,
        ]);

        $response->assertStatus(200);
    }

       /**
     * A basic feature test example.
     *
     * @test
     */
    public function dispose_reservation()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::find('john@dd.com'));
        $response = $this->post('api/patient/cancel', [
            'id' => 11,
        ]);

        $response->assertStatus(200);
    }

         /**
     * A basic feature test example.
     *
     * @test
     */
    public function show_reservation()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::find('john@dd.com'));
        $response = $this->get('api/patient/reserves');

        $response->assertStatus(200);
    }
         /**
     * A basic feature test example.
     *
     * @test
     */
    public function Sho_table()
    {
        $this->withoutExceptionHandling();
        $doctor = Doctor::find('johdn@dd.com');
        $this->actingAs($doctor);
        $response = $this->get('api/doctor/tables');
        $response->assertStatus(200);
    }
         /**
     * A basic feature test example.
     *
     * @test
     */
    public function show_disp()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::find('john@gmail.com'));
        $response = $this->get('api/showDisap/johdn@dd.com?page=1');
        $date = DateTime::createFromFormat('H:i', '27/03/2021');

        print($date);
        print( Date('H:i', strtotime('+2 Hours')));
        $response->assertStatus(200);
    }

        /**
     * A basic feature test example.
     *
     * @test
     */
    public function next_show_disap()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::find('john@dd.com'));

        $response = $this->post('api/patient/reserve', [
            'doctor_email' => 'johdn@dd.com',
            'date' => '11/04/2021',
            'day' => 'sun',
            "interval" => '05:35',
            'state' => 0
        ]);
        // $doctor = Doctor::find('johdn@dd.com');

        // if($doctor['is_first_come']){

        //     $doctor->with(['reserves' => function($value){

        //         $value->where('date', '2021-03-22')->where('interval', '05:30');
        //     }]);
        //     print(response()->json($doctor));

        // }

        $response->assertStatus(200);
    }
}
