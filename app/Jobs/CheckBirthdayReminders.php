<?php

namespace App\Jobs;

use App\Models\Birthday;
use App\Models\GlobalSchedule;
use App\Models\Reminder;
use App\Notifications\RemindOfBirthday;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckBirthdayReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $now;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($forDate = null)
    {
        $this->now = $forDate ?? now();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // check global schedule
        $globalSchedules = GlobalSchedule::whereDay('remind_at', $this->now)
            ->whereMonth('remind_at', $this->now)
            ->with('birthday', 'reminder')
            ->get();

        foreach ($globalSchedules as $schedule) {
            $this->notifyUserForBirthday($schedule->birthday, $schedule->reminder);
        }

        // then check individual birthday reminders
        $birthdayReminders = Reminder::whereHasMorph('remindable', [Birthday::class])
            ->whereDay('remind_at', $this->now)
            ->whereMonth('remind_at', $this->now)
            ->with('remindable')
            ->get();

        foreach ($birthdayReminders as $birthdayReminder) {
            $this->notifyUserForBirthday($birthdayReminder->remindable, $birthdayReminder);
        }
    }

    protected function notifyUserForBirthday(Birthday $birthday, Reminder $reminder)
    {
        $birthday->notify((new RemindOfBirthday($reminder)));
    }
}
