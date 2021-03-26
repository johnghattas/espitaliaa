<?php

namespace Tests\Unit;

use App\Models\Doctor;
use App\Models\User;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;


use App\Models\Garage;
use App\Models\Reservation;
use App\Models\TimeTable;
use Illuminate\Support\Facades\Schema;
//use PHPUnit\Framework\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends TestCase
{
    // use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testsBasicTest()
    {
        $this->withoutExceptionHandling();
        $doctor = Doctor::create([
            'name' => "cairo",
            'email' => "ff",
            'password' => "55",
            'phone' => "1234",
            'spatial' => "this name",
            'is_first_come' => true,
            'duration_min' => 50
        ]);
        $patient =  User::create(
            [
                'name' => "cairo",
                'email' => "ffff",
                'password' => "55",
                'phone' => "12346",

            ]
        );
        //make pationt to doctor
        $doctor->pationts()->attach( $patient->email, ['date' => '06/05/1922','day'=> 'sun', 'interval'=>'03:30', 'state' => 0]);
        $doctor->pationts()->attach( $patient->email, ['date' => '06/05/2002','day'=> 'mon', 'interval'=>'03:30', 'state' => 0]);
        $doctor->pationts()->attach( $patient->email, ['date' => '06/05/1999','day'=> 'tue', 'interval'=>'03:30', 'state' => 0]);
        $this->assertEquals("cairo", Doctor::first()->name);
        $this->assertCount(1, Reservation::all());
    }

    /** @test */
    public function ftimeTable()
    {
        $this->withoutExceptionHandling();
        // $doctor = Doctor::create([
        //     'name' => "cairo",
        //     'email' => "22j",
        //     'password' => "55",
        //     'phone' => "1070",
        //     'spatial' => "this name",
        //     'is_first_come' => true,
        // ]);
        $doctor = Doctor::find('johdn@dd.com');
        $patient =  TimeTable::create(
            [
                    'day' => "sun",
                    'is_avail' => true,
                    'start_time' => "03:30",
                    'end_time' => "09:30",
                    'count_att' => 5,
            ]
        );
        $doctor->timeTables()->save($patient);

        // $this->assertCount(1, TimeTable::all());
    }


     /** @test */
     public function timeTabletwo()
     {
         $this->withoutExceptionHandling();
         $doctor = Doctor::create([
             'name' => "cairo",
             'email' => "22",
             'password' => "55",
             'phone' => "100",
             'spatial' => "this name",
             'is_first_come' => true,
         ]);
         $patient =  TimeTable::create(
             [
                     'day' => "22",
                     'is_avail' => "55",
                     'start_time' => "03:30",
                     'end_time' => "09:30",
                     'count_att' => 5,
             ]
         );
         $doctor->timeTables()->save($patient);

         print($doctor->timeTables->where('day', '22')->where('doctor_id', "22")->first());
        //  $this->assertEquals('22' , $doctor->timeTables)
         $this->assertCount(1, TimeTable::all());
     }
}
