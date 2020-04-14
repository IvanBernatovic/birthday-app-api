<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Birthday extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'date'];

    protected $dates = ['date'];

    /**
     * Returns collection of birthdays
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
     * Add gifts to the birthday.
     *
     * @param array $gifts
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function addGifts($tasks)
    {
        return $this->gifts()->createMany($tasks);
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
     * @return Gift
     */
    public function addReminder($data)
    {
        return $this->reminders()->create($data);
    }
}
