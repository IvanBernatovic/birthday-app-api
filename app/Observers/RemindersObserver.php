<?php

namespace App\Observers;

use App\Models\Reminder;

class RemindersObserver
{
    public function created(Reminder $reminder)
    {
        if ($reminder->isBirthdayReminder()) {
            $reminder->updateIndividualSchedule();
        }
    }

    public function saved(Reminder $reminder)
    {
        if ($reminder->isGlobalReminder()) {
            $reminder->updateGlobalSchedules();
        }
    }
}
