<?php

namespace Tests\Unit;

use App\Models\Reminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BirthdayReminderTest extends TestCase
{
    use RefreshDatabase;

    protected function calculateRemindAt($beforeAmount, $beforeUnit, $date)
    {
        $remindAt = clone $date;

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
}
