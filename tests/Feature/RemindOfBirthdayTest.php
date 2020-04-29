<?php

namespace Tests\Feature;

use App\Jobs\CheckBirthdayReminders;
use App\Models\Birthday;
use App\Models\GlobalSchedule;
use App\Models\Reminder;
use App\Notifications\RemindOfBirthday;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Notification;
use Queue;
use Tests\TestCase;

class RemindOfBirthdayTest extends TestCase
{
    use RefreshDatabase;

    public function testCheckBirthdayRemindersCommandIsRanByScheduler()
    {
        Queue::fake();

        // simulate the time when job should be pushed by scheduler
        Carbon::setTestNow('00:05');

        $this->artisan('schedule:run');
        Queue::assertPushed(CheckBirthdayReminders::class);
    }

    public function testCheckBirthdayRemindersJobCanBePushed()
    {
        Queue::fake();

        $this->artisan('run:check-birthday-reminders');

        Queue::assertPushed(CheckBirthdayReminders::class);
    }

    public function testUserIsNotifiedOnBirthday()
    {
        $user = factory(User::class)->create();
        $birthday = factory(Birthday::class)->create([
            'user_id' => $user->id,
        ]);

        // simulate the time of the reminder
        Carbon::setTestNow($birthday->date);

        Notification::fake();

        Notification::assertNothingSent();

        CheckBirthdayReminders::dispatchNow();

        Notification::assertSentTo(
            $birthday, RemindOfBirthday::class, function ($notification) use ($birthday) {
                $mail = $notification->toMail($birthday);
                $this->assertStringContainsString('today', $mail->render());
                // call this to get 100% test coverage, mocking mails and notifications doesn't call it
                $birthday->routeNotificationForMail($notification);

                return true;
            }
        );
    }

    public function testUserIsNotifiedForIndividualReminder()
    {
        $reminder = factory(Reminder::class)->state('birthday')->create();

        // simulate the time of the reminder
        Carbon::setTestNow($reminder->remind_at);

        Notification::fake();
        Notification::assertNothingSent();

        CheckBirthdayReminders::dispatchNow();

        Notification::assertSentTo(
            $reminder->remindable, RemindOfBirthday::class
        );
    }

    public function testUserIsNotifiedForCustomGlobalReminder()
    {
        $reminder = factory(Reminder::class)->state('global')->create();
        $birthday = factory(Birthday::class)->create([
            'user_id' => $reminder->remindable_id,
        ]);

        $schedule = GlobalSchedule::where('birthday_id', $birthday->id)
            ->where('reminder_id', $reminder->id)
            ->first();

        // simulate the time of the reminder
        Carbon::setTestNow($schedule->remind_at);

        Notification::fake();
        Notification::assertNothingSent();

        CheckBirthdayReminders::dispatchNow();

        Notification::assertSentTo(
            $birthday, RemindOfBirthday::class, function ($notification) use ($birthday) {
                $mail = $notification->toMail($birthday);
                $this->assertStringContainsString($birthday->name, $mail->render());

                return true;
            }
        );
    }
}
