<?php

namespace App\Observers;

use App\Models\Birthday;

class BirthdayObserver
{
    public function saved(Birthday $birthday)
    {
        // only update schedules if date is changed
        $changedAttributes = $birthday->getDirty();
        if (!array_key_exists('date', $changedAttributes)) {
            return;
        }

        $birthday->load('global_schedules.reminder', 'reminders');

        // update global schedule
        if ($birthday->global_schedules->count()) {
            foreach ($birthday->global_schedules as $globalSchedule) {
                $globalSchedule->reminder->updateBirthdayGlobalSchedule($birthday);
            }
        } else {
            $globalReminders = $birthday->user->reminders;

            foreach ($globalReminders as $reminder) {
                $reminder->updateBirthdayGlobalSchedule($birthday);
            }
        }

        // update individual birthday reminders
        $birthday->reminders->each(function ($reminder) {
            $reminder->updateIndividualSchedule();
        });
    }

    public function deleting(Birthday $birthday)
    {
        $birthday->global_schedules()->delete();
        $birthday->reminders()->update(['deleted_at' => now()]);
    }
}
