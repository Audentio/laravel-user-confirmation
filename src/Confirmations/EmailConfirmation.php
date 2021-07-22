<?php

namespace Audentio\LaravelUserConfirmation\Confirmations;

class EmailConfirmation extends AbstractConfirmation
{
    public function getConfirmationType(): string
    {
        return 'email';
    }

    public function getConfirmationCtaText(): string
    {
        return 'Confirm Email';
    }

    public function getConfirmationSubject(): string
    {
        return 'Confirm your email address';
    }

    public function getConfirmationDescription(): string
    {
        return 'Before continuing to use the site, you must confirm your email address.';
    }

    public function canSendConfirmation(): bool
    {
        if ($this->user->isEmailVerified()) {
            return false;
        }

        return true;
    }

    protected function _confirm(?string $extra = null, ?string &$error = null): bool
    {
        $this->user->markEmailAsVerified();

        return true;
    }
}