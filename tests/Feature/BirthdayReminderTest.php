<?php

namespace Tests\Feature;

use App\Models\Birthday;
use App\Models\Reminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AttachJwtToken;
use Tests\TestCase;

class BirthdayReminderTest extends TestCase
{
    use RefreshDatabase, AttachJwtToken;

    public function testUserCanAddBirthdayReminder()
    {
        $birthday = factory(Birthday::class)->create();
        $reminder = factory(Reminder::class)->state('birthday')->make();

        $this->actingAs($birthday->user)
            ->post(route('birthday-reminders.store', $birthday, false), [
                'before_amount' => $reminder->before_amount,
                'before_unit' => $reminder->before_unit,
            ])
            ->assertStatus(200);
    }

    public function testUserCanDeleteBirthdayReminder()
    {
        $reminder = factory(Reminder::class)->state('birthday')->create();

        $this->actingAs($reminder->remindable->user)
            ->delete(route('birthday-reminders.delete', [$reminder->remindable, $reminder], false))
            ->assertStatus(200);
    }
}
