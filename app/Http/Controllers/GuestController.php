<?php

namespace App\Http\Controllers;

use App\HelperMethods\JsonReturn;
use App\Models\Doctor;
use App\Models\Reservation;
use DateTime;
use Illuminate\Http\Request;


class GuestController extends Controller
{
    use JsonReturn;

    public function getAllDoctors(){

        return $this->dataJson(Doctor::paginate(10));
    }


    public function showDisap(string $email, Request $request)
    {

        $doctor = Doctor::find($email);
        $indexPage = $request['page'];

        $table = $doctor->timeTables->toArray();

        $curDate = Date('Y-m-d', strtotime("+" . ((($indexPage - 1) * 3)) . " days"));
        $dateAfter3 = Date('Y-m-d', strtotime("+" . ((($indexPage - 1) * 3) + 3) . " days"));

        $reserves = Reservation::whereRaw('doctor_id = ? and DATE(date) >= ? and DATE(date) <= ? and state != -1', [$doctor->email, $curDate, $dateAfter3])->get(['date', 'interval'])->toArray();


        if ($indexPage == 1) {
            $data = ['info' => $doctor, 'days' => []];
        } else
            $data = ['days' => []];

        // fetch intervals
        foreach ($reserves  as  $reserve) {
            $this->putDatesWithInterval($data, $reserve);
        }

        //to compleat dates
        for ($i = 0; $i < 3; $i++) {

            $curDate = Date('d/m/Y', strtotime("+" . ((($indexPage - 1) * 3) + $i) . " days"));

            $this->putDatesWithInterval($data, ['date' => (string) $curDate, 'interval' => null]);

            $this->putTableInArray($data, $table, $curDate);
        }

        //sort the array after change it to day with date
        uksort($data['days'], array($this, 'compare'));
        foreach ($data['days']  as  $k => $v) {
            $nDate = $this->handleDays($k);

            $data['days'][$nDate] = $data['days'][$k];
            unset($data['days'][$k]);
        }
        return $this->dataJson($data);
    }

    private function compare($a, $b)
    {
        $a = $this->ctDT($a)->format('Y-m-d');
        $b = $this->ctDT($b)->format('Y-m-d');

        return  strtotime($a) -  strtotime($b);
    }

    private function putTableInArray(array &$data, array $table, $day)
    {
        $cDay = $this->ctDT($day)->format('D');

        $tab = array_filter($table, function ($tab) use ($cDay) {
            return $tab['day'] == strtolower(substr($cDay, 0, 3));
        });


        $data['days'][$day]['table'] = array_pop($tab);
    }

    private function putDatesWithInterval(array &$data, $reserve)
    {

        $date = $reserve['date'];
        $hasInterval = (bool) $reserve['interval'] != NULL;
        $isFound = array_key_exists($date, $data['days']);

        if ($isFound) {
            if (!$hasInterval) {

                return;
            }
            array_push($data['days'][(string) $date]['intervals'], $reserve['interval']);
        } else {
            $data['days'][$date]['intervals'] = $hasInterval ? array($reserve['interval']) : array();
        }
    }

    private function handleDays($day): string
    {
        $cDay = Date('d/m/Y');
        $cTom = Date('d/m/Y', strtotime('+1 days'));

        // $shortDay = $this->ctDT($day)->format('d/m/Y');


        $dayFormat = $this->ctDT($day)->format('D');
        if ($cDay === $day) {
            $curDate = "Today";
        } else if ($day == $cTom) {
            $curDate = 'Tomorrow';
        } else {
            $curDate = '' . $dayFormat . ' ' . $day;
        }
        return $curDate;
    }


}
