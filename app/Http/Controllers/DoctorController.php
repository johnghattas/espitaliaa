<?php

namespace App\Http\Controllers;

use App\HelperMethods\JsonReturn;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\TimeTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    use JsonReturn;

    public function __construct()
    {
        $this->middleware(['auth:doctor']);
    }
    //put data of doctor
    public function insertOrUpdate(Request $request)
    {
        $validator = request()->validate([
            'is_first_come' => 'required|bool',
            'duration_min' => 'required|int',

            'tableList'=>'required',
            'tableList.*.day' => 'required|string|max:3',
            'tableList.*.is_avail' => 'required|boolean',
            'tableList.*.start_time' => 'sometimes|string|min:5|max:5|date_format:H:i',
            'tableList.*.end_time' => 'sometimes|string|min:5|max:5|date_format:H:i|after_or_equal:tableList.*.start_time',
            'tableList.*.count_att' => 'sometimes|integer',
        ]);

        $isFirstCome = $validator['is_first_come'];
        $duration = $validator['duration_min'];
        $doctor = Doctor::find(Auth::id());

        $doctor->is_first_come = $isFirstCome;
        if (!$isFirstCome && $duration) {
            $doctor->duration_min = $duration;
        }
        $doctor->save();

        //isert or update time table
        $this->insertTimeTable($validator['tableList'], $doctor);

        return $this->dataJson('Done');
    }

    public function getTables(){
        $doctor = Doctor::find(Auth::id());

        return $this->dataJson(['is_first_come'=> $doctor['is_first_come'], 'duration_min' => $doctor['duration_min'],'tables'=>$doctor->timeTables]);
    }


    public function showAllReservations(/* string $email */){
        // $doctor = Doctor::findOrFail($email);
        $doctor = Auth::user();
        $this->dataJson(Reservation::with('patient')->where('doctor_id' , $doctor->email)->latest('date') ->get());
    }

    public function changeState( Request $request){
        $validator = request()->validate([
            'id' => "required|int",
            'state' => 'required|gte:-1|lte:3'
        ]);
        $doctor = Auth::user();

        $reser = Reservation::findOrFail($validator['id']);
        if($reser->doctor_id !== $doctor->email){
            return $this->errorJson('not Authorized', 401);
        }
        $reser->state = $validator['state'];
        $reser->save();
        return $this->dataJson('Done');
    }

    /**
     *
     * @return void
     */
    private function insertTimeTable(array $tableList, Doctor $doctor): void
    {

        foreach ($tableList as $tTime) {
            $tableExist =  $doctor->timeTables->where('day', $tTime['day'])->where('doctor_id', $doctor['email'])->first();

            if ($tableExist) {
                $tableExist->is_avail = $tTime['is_avail'];
                $tableExist->start_time = $tTime['start_time'];
                $tableExist->end_time = $tTime['end_time'];
                $tableExist->count_att = $tTime['count_att'];
                $tableExist->save();
            } else
                $doctor->timeTables()->save(TimeTable::create(
                    [
                        'day' => $tTime['day'],
                        'is_avail' => $tTime['is_avail'],
                        'start_time' => $tTime['start_time'],
                        'end_time' => $tTime['end_time'],
                        'count_att' => $tTime['count_att'],
                    ]
                ));
        }
    }
}
