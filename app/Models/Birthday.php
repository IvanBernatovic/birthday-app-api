<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Birthday extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'date'];

    protected $dates = ['date'];

    /**
     * Returns instance of user
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns collection of gifts
     *
     * @return Collection
     */
    public function gifts()
    {
        return $this->hasMany(Gift::class);
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
     * Returns collection of global schedules
     *
     * @return Collection
     */
    public function global_schedules()
    {
        return $this->hasMany(GlobalSchedule::class);
    }

    /**
     * Add a gift to the birthday.
     *
     * @param  string $body
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addGift($body)
    {
        return $this->gifts()->create(compact('body'));
    }

    /**
     * Add a reminder to the birthday
     *
     * @param array $body
     * @return Reminder
     */
    public function addReminder($data)
    {
        return $this->reminders()->create($data);
    }
}
