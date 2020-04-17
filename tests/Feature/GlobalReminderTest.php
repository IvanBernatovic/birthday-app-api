<?php

namespace Tests\Feature;

use App\Models\Birthday;
use App\Models\Reminder;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AttachJwtToken;
use Tests\TestCase;

class GlobalReminderTest extends TestCase
{
    use RefreshDatabase, AttachJwtToken;

    public function testUserHasGlobalReminderByDefault()
    {
        $user = factory(User::class)->create();

        $reminder = $user->reminders()->first();

        $this->assertEquals(1, $reminder->before_unit);
        $this->assertEquals(0, $reminder->before_amount);
    }

    public function testUserCanGetGlobalReminders()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('reminders'), [], false)->assertStatus(200);
    }

    public function testUserCanUpdateGlobalReminders()
    {
        $user = factory(User::class)->create();
        $birthdays = factory(Birthday::class, random_int(1, 5))->create([
            'user_id' => $user->id,
        ]);

        $reminder = factory(Reminder::class)->state('global')->make();

        $this->actingAs($user)
            ->post(route('reminders.store', [], false), [
                'before_unit' => $reminder->before_unit,
                'before_amount' => $reminder->before_amount,
            ])
            ->assertStatus(200);
    }

    public function testUserCanDeleteGlobalReminder()
    {
        $reminder = factory(Reminder::class)->state('global')->create();

        $this->actingAs($reminder->remindable)
            ->delete(route('reminders.delete', $reminder, false))
            ->assertStatus(200);
    }
}
