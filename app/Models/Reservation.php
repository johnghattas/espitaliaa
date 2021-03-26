<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Reservation extends Pivot
{


    public $table = 'reservations';
    public $primaryKey = 'id';
    public $timestamps = false;


    protected $filiable = [
        'id',
        'date',
        'day',
        'interval',
        'state'
    ];




    public function getFormattedDateAttribute()
    {
        return $this->date->format('d/m/Y');
    }

    public function getStateAttribute()
    {
        $state = "";
        switch ($this->attributes['state']) {
            case -1:
                $state = "Canceled";
                break;
            case 0:
                $state = "waiting";
                break;
            case 1:
                $state = 'successfull';
                break;
            case 1:
                $state = 'attendee';
                break;
        }


        return $state;
    }



    public function setDateAttribute($date)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $date);
    }

    public function getDateAttribute()
    {
        $format = Carbon::parse( $this->attributes['date'])->format('d/m/Y');
        return $format;

    }
    /**
     * Get the patient that owns the Reservation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pationt_id', 'email');
    }

     /**
     * Get the doctor that owns the Reservation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'email');
    }
}
