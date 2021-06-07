<?php

namespace Audentio\LaravelUserConfirmation\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface UserConfirmationModelInterface
{
    public function user(): BelongsTo;
    public function tokenMatches(string $token): bool;
    public function generateConfirmationToken(): void;
}