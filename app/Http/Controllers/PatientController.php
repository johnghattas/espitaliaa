<?php

namespace App\Http\Controllers;

use App\HelperMethods\JsonReturn;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\TimeTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    use JsonReturn;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function doctorReservation()
    {
        //get yesterday date
        $date = Date('d/m/Y', strtotime("-1 days"));

        $validator = request()->validate([
            'doctor_email' => 'required|email',
            'date' => 'required|date_format:d/m/Y|after:' . $date,
            'day' => 'required|max:3',
            "interval" => 'sometimes|date_format:H:i',
            'state' => 'required|integer|gte:-1|lte:3',
        ]);
        $patient =  User::find(Auth::id());
        $doctor = Doctor::find($validator['doctor_email']);

        $vDate = $this->ctDT($validator['date'])->format('Y-m-d');
        $isReserve = Reservation::where('doctor_id', $doctor['email'])->where('pationt_id', $patient->email)->where('date', $vDate)->count();

        if($isReserve){
            return $this->errorJson('couldn\'t reserve in one day more than ');
        }


        if($doctor['is_first_come']){

            $times = Reservation::where('doctor_id', $doctor['email'])
            ->where('date', $vDate)->count();

             $countAtt = $doctor->timeTables->where('day', strtolower($validator['day']))->first()['count_att'];

            if($times > $countAtt){

                return $this->errorJson('doctor compleate the count');
            }

        }else{
            $times = Reservation::where('doctor_id', $doctor['email'])
            ->where('date', $vDate)->where('interval', $validator['interval'])->count();

            if($times){
                return $this->errorJson('this time not available');
            }
        }


        $patient->doctorsTime()->attach($doctor->email, [
            'date' => $validator['date'],
            'day' => $validator['day'],
            'interval' => $validator['interval'],
            'state' => $validator['state']
        ]);

        return $this->dataJson('Done');
    }

    public function disposeReserv()
    {
        $validator = request()->validate([
            'id' => "required|integer",
        ]);
        $patient = Auth::user();

        $reser = Reservation::findOrFail($validator['id']);
        if ($reser->pationt_id !== $patient->email) {
            return $this->errorJson('not Authorized', 401);
        }
        $reser->state = -1;
        $reser->save();
        return $this->dataJson('Done');
    }

    public function showAllReservations()
    {
        $patient = User::find(Auth::id());
        return $this->dataJson(
            Reservation::with('doctor')
                ->where('pationt_id', $patient->email)
                ->latest('date')->get()
        );
    }
}
