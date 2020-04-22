<?php

namespace App;

use App\Models\Birthday;
use App\Models\Reminder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Get the identifier that will be stored in the subject claim of the JWT.
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    // Return a key value array, containing any custom claims to be           added to the JWT.
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Returns collection of birthdays
     *
     * @return Collection
     */
    public function birthdays()
    {
        return $this->hasMany(Birthday::class);
    }

    /**
     * Returns collection of reminders
     *
     * @return Collection
     */
    public function reminders()
    {
        return $this->morphMany(Reminder::class, 'remindable');
    }

    /**
     * Add global reminder
     *
     * @param array $data
     * @return Reminder
     */
    public function addReminder($data)
    {
        return $this->reminders()->create($data);
    }

}
