<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeTable extends Model
{
    use HasFactory;

    protected $table = 'time_tables';
    public $timestamps = false;



    protected $fillable = [
        'doctor_id',
        'day',
        'is_avail',
        'start_time',
        'end_time',
        'count_att'
    ];

    /**
     * Get the doctor associated with the TimeTable
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'email');
    }


}
