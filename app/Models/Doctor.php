<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Doctor extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $table = "doctors";
    public $timestamps = false;
    protected $primaryKey = 'email';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'spatial',
        'is_first_come',
        'duration_min'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email' => 'string',
        'is_first_come' =>'integer',
        'duration_min' =>'integer',
    ];

    /**
     * Get all of the timeTables for the Doctor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeTables(): HasMany
    {
        return $this->hasMany(TimeTable::class, 'doctor_id', 'email');
    }

    /**
     * The pationts that belong to the Doctor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pationts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'reservations', 'doctor_id', 'pationt_id')->using(Reservation::class);
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
