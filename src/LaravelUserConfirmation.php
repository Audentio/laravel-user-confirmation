<?php

namespace Audentio\LaravelUserConfirmation;

use App\Models\User;
use Audentio\LaravelUserConfirmation\Confirmations\AbstractConfirmation;

class LaravelUserConfirmation
{
    protected static string $baseConfirmationUrl;

    public static function sendUserConfirmation(User $user, string $handlerClass, array $data = []): bool
    {
        /** @var AbstractConfirmation $confirmation */
        $confirmation = new $handlerClass($user);

        if (!$confirmation->canSendConfirmation()) {
            return false;
        }

        $confirmation->sendConfirmation($data);
        return true;
    }

    public static function getBaseConfirmationUrl(): string
    {
        if (!isset(self::$baseConfirmationUrl)) {
            throw new \RuntimeException('Base confirmation URL has not been set.');
        }

        return self::$baseConfirmationUrl;
    }

    public static function setBaseConfirmationUrl(string $baseUrl): void
    {
        self::$baseConfirmationUrl = $baseUrl;
    }
}