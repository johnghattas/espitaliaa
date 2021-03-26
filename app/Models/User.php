<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $table = "patients";
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
        'email' => 'string'
    ];


    /**
     * The doctors that belong to the Doctor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function doctorsTime(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class, 'reservations', 'pationt_id', 'doctor_id')->using(Reservation::class)->withPivot('date', 'day', 'interval', 'state')->orderBy('date', 'desc')/* withPivot('day', 'interval', 'state') */;
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
