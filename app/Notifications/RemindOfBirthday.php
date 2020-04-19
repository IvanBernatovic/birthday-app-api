<?php

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemindOfBirthday extends Notification
{
    use Queueable;

    protected $reminder;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reminder $remidner)
    {
        $this->reminder = $remidner;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $nextBirthday = $notifiable->date->copy()->setYear(now()->format('Y'));

        return (new MailMessage)->markdown('mail.remind-of-birthday', [
            'birthday' => $notifiable,
            'reminder' => $this->reminder,
        ])->subject("{$notifiable->name} has birthday on {$nextBirthday->isoFormat('dddd, MMMM Do')}");
    }
}
