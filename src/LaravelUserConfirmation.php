<?php

namespace Audentio\LaravelUserConfirmation;

use App\Models\User;
use Audentio\LaravelUserConfirmation\Confirmations\AbstractConfirmation;

class LaravelUserConfirmation
{
    protected static string|\Closure $baseConfirmationUrl;

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

    public static function getBaseConfirmationUrl($notifiable): string
    {
        if (!isset(self::$baseConfirmationUrl)) {
            throw new \RuntimeException('Base confirmation URL has not been set.');
        }

        if (self::$baseConfirmationUrl instanceof \Closure) {
            $function = self::$baseConfirmationUrl;
            return $function($notifiable);
        }

        return self::$baseConfirmationUrl;
    }

    public static function setBaseConfirmationUrl(string|\Closure $baseUrl): void
    {
        self::$baseConfirmationUrl = $baseUrl;
    }
}
