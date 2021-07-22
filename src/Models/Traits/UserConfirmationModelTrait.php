<?php

namespace Audentio\LaravelUserConfirmation\Models\Traits;

use Audentio\LaravelBase\Utils\KeyUtil;

trait UserConfirmationModelTrait
{
    public function tokenMatches(string $token): bool
    {
        return $token === $this->token;
    }

    public function generateConfirmationToken(): void
    {
        $token = KeyUtil::guid($this->generateKeySeedBytes);
        $this->token = $token;
    }

    protected function initializeUserConfirmationModelTrait(): void
    {
        $this->fillable = array_merge([
            'user_id', 'handler_class', 'token'
        ], $this->fillable);

        $this->casts['data'] = 'json';
    }
}