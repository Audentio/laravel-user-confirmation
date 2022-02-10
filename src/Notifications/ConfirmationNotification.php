<?php

namespace Audentio\LaravelUserConfirmation\Notifications;

use Audentio\LaravelUserConfirmation\Confirmations\AbstractConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmationNotification extends Notification
{
    protected AbstractConfirmation $confirmation;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->confirmation->getConfirmationSubject())
            ->line($this->confirmation->getConfirmationDescription())
            ->action($this->confirmation->getConfirmationCtaText(), $this->confirmation->getConfirmationUrl($notifiable));
    }

    public function toArray($notifiable): array
    {
        return [];
    }

    public function __construct(AbstractConfirmation $confirmation)
    {
        $this->confirmation = $confirmation;
    }
}
