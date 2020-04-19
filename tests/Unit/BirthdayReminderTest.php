<?php

namespace Tests\Unit;

use App\Models\Birthday;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BirthdayReminderTest extends TestCase
{
    use RefreshDatabase;

    protected function calculateRemindAt($beforeAmount, $beforeUnit, $date)
    {
        $remindAt = $date->copy();

        if ($beforeUnit == 1) {
            $remindAt = $date->subDays($beforeAmount);
        } else if ($beforeUnit == 2) {
            $remindAt = $date->subWeeks($beforeAmount);
        }

        return $remindAt;
    }

    public function testBirthdayReminderHasCorrectRemindAt()
    {
        $reminder = factory(Reminder::class)->state('birthday')->create();

        $remindAt = $this->calculateRemindAt($reminder->before_amount, $reminder->before_unit, $reminder->remindable->date);

        $this->assertEquals($reminder->remind_at->toDateTimeString(), $remindAt->toDateTimeString());
    }

    public function testBirthdayReminderIsCorrectlyUpdatedAfterBirthdayUpdate()
    {
        $reminder = factory(Reminder::class)->state('birthday')->create();

        $reminder->remindable->update([
            'date' => $newDate = Carbon::parse('2005-05-05'),
        ]);

        $reminder->refresh();

        $remindAt = $this->calculateRemindAt($reminder->before_amount, $reminder->before_unit, $newDate);

        $this->assertEquals($reminder->remind_at->toDateTimeString(), $remindAt->toDateTimeString());
    }

    public function testGlobalScheduleIsCorrectlySet()
    {
        $birthday = factory(Birthday::class)->create();
        $globalReminders = $birthday->user->reminders()->with('global_schedules')->get();

        $globalReminders->each(function ($reminder) use ($birthday) {
            $schedule = $reminder->global_schedules->where('birthday_id', $birthday->id)->first();

            $remindAt = $this->calculateRemindAt($reminder->before_amount, $reminder->before_unit, $birthday->date);
            $this->assertEquals($schedule->remind_at->toDateTimeString(), $remindAt->toDateTimeString());
        });
    }
}
