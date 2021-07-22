<?php

namespace Audentio\LaravelUserConfirmation\Confirmations;

use App\Models\User;
use Audentio\LaravelUserConfirmation\Notifications\UserEmailChangedNotification;

class EmailChangeConfirmation extends AbstractConfirmation
{
    public function getNotifiable()
    {
        return $this->getUserConfirmation()->data['new_email'];
    }

    public function getConfirmationType(): string
    {
        return 'emailChange';
    }

    public function getConfirmationCtaText(): string
    {
        return 'Confirm New Email';
    }

    public function getConfirmationSubject(): string
    {
        return 'Confirm your new email address';
    }

    public function getConfirmationDescription(): string
    {
        return 'Before continuing to use the site, you must confirm your new email address.';
    }

    public function canSendConfirmation(): bool
    {
        return true;
    }

    protected function _confirm(?string $extra = null, ?string &$error = null): bool
    {
        if (!$this->user->isEmailVerified()) {
            $this->user->markEmailAsVerified(false);
        }

        $newEmail = $this->getUserConfirmation()->data['new_email'];
        $oldEmail = $this->getUserConfirmation()->data['old_email'];

        if (User::where('email', $newEmail)->exists()) {
            $error = 'Your new email is already in use.';
            return false;
        }

        $this->user->email = $newEmail;
        $this->user->save();

        \Notification::route('mail', $oldEmail)
            ->notify(new UserEmailChangedNotification($newEmail));

        return true;
    }
}