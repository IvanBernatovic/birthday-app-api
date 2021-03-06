<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class Reminder extends Model
{
    use SoftDeletes;

    protected $fillable = ['before_amount', 'before_unit', 'remind_at'];

    protected $touches = ['remindable'];

    protected $dates = ['remind_at'];

    public function remindable()
    {
        return $this->morphTo();
    }

    /**
     * Returns global birthday schedules for the reminder
     *
     * @return Collection
     */
    public function global_schedules()
    {
        return $this->hasMany(GlobalSchedule::class);
    }

    /**
     * Checks if reminder belongs to birthday
     *
     * @return boolean
     */
    public function isBirthdayReminder()
    {
        return $this->remindable_type == 1;
    }

    /**
     * Checks if global reminder
     *
     * @return boolean
     */
    public function isGlobalReminder()
    {
        return $this->remindable_type == 2;
    }

    public function calculateRemindAt($date)
    {
        $remindAt = $remindAt = $date->copy();

        switch ($this->before_unit) {
            case 1:
                $remindAt = $remindAt->subDays($this->before_amount);
                break;
            case 2:
                $remindAt = $remindAt->subWeeks($this->before_amount);
                break;
        }

        return $remindAt;
    }

    /**
     * Updates individual reminder time for birthday
     *
     * @return void
     */
    public function updateIndividualSchedule()
    {
        $birthdayDate = $this->remindable->date->copy();

        $this->update([
            'remind_at' => $this->calculateRemindAt($birthdayDate),
        ]);
    }

    /**
     * Updates all global schedules for the reminder
     *
     * @return void
     */
    public function updateGlobalSchedules()
    {
        $user = $this->remindable;
        $this->load('global_schedules');

        foreach ($user->birthdays as $birthday) {
            $this->updateBirthdayGlobalSchedule($birthday);
        }
    }

    /**
     * Updates individual global schedule for the reminder
     *
     * @param Birthday $birthday
     * @return void
     */
    public function updateBirthdayGlobalSchedule(Birthday $birthday)
    {
        $birthdayGlobalSchedule = $this->global_schedules->firstWhere('birthday_id', $birthday->id);

        if (!$birthdayGlobalSchedule) {
            $this->global_schedules()->create([
                'birthday_id' => $birthday->id,
                'remind_at' => $this->calculateRemindAt($birthday->date),
            ]);
        } else {
            $birthdayGlobalSchedule->update([
                'remind_at' => $this->calculateRemindAt($birthday->date),
            ]);
        }
    }

    public function getDiffForHumans()
    {
        $timeUnit = $this->before_unit == 1 ? 'day' : 'week';

        if ($this->before_amount == 0) {
            return 'today';
        }

        $timeUnit = Str::plural($timeUnit, $this->before_amount);

        return "in {$this->before_amount} {$timeUnit}";
    }
}
