<?php

namespace Audentio\LaravelUserConfirmation\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserEmailChangedNotification extends Notification
{
    protected string $newEmail;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your email has been updated')
            ->line('Your email has been changed to \'' . $this->newEmail . '\'. If you did not request this ' .
                'change please contact support.');
    }

    public function toArray($notifiable): array
    {
        return [];
    }

    public function __construct(string $newEmail)
    {
        $this->newEmail = $newEmail;
    }
}