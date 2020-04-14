<?php

namespace App\Observers;

use App\Models\Reminder;

class RemindersObserver
{
    public function saving(Reminder $reminder)
    {
        $birthdayDate = $reminder->remindable->date;

        switch ($reminder->before_unit) {
            case 1:
                $reminder->remind_at = $birthdayDate->subDays($reminder->before_amount);
                break;
            case 2:
                $reminder->remind_at = $birthdayDate->subWeeks($reminder->before_amount);
                break;
            case 3: 
                $reminder->remind_at = $birthdayDate->subMonths($reminder->before_amount);
            break;
        }
    }
}
