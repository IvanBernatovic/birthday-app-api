<?php

namespace App\Observers;

use App\Models\Reminder;

class RemindersObserver
{
    public function saved(Reminder $reminder)
    {
        if ($reminder->isBirthdayReminder()) {
            $reminder->updateIndividualSchedule();
            return;
        } else if ($reminder->isGlobalReminder()) {
            $reminder->updateGlobalSchedules();
        }
    }
}
