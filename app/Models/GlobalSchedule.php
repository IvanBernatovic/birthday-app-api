<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalSchedule extends Model
{
    protected $fillable = ['birthday_id', 'reminder_id', 'remind_at'];

    protected $dates = ['remind_at'];

    /**
     * Returns birthday for given schedule entry
     *
     * @return Birthday
     */
    public function birthday()
    {
        return $this->belongsTo(Birthday::class);
    }

    /**
     * Returns reminder for given schedule entry
     *
     * @return Reminder
     */
    public function reminder()
    {
        return $this->belongsTo(Reminder::class);
    }
}
