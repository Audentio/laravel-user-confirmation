<?php

namespace Audentio\LaravelUserConfirmation\Confirmations;

class PasswordResetConfirmation extends AbstractConfirmation
{
    public function getConfirmationType(): string
    {
        return 'passwordReset';
    }

    public function getConfirmationCtaText(): string
    {
        return 'Update password';
    }

    public function getConfirmationSubject(): string
    {
        return 'Reset your password';
    }

    public function getConfirmationDescription(): string
    {
        return 'A password reset has been requested for your account. Click the link below to update your password.';
    }

    protected function _confirm(?string $extra = null): bool
    {
        if (empty($extra) || strlen($extra) < 5) {
            return false;
        }
        $this->user->password = $extra;

        return true;
    }

}