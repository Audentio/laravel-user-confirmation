<?php

namespace Audentio\LaravelUserConfirmation\Confirmations;

use App\Models\User;
use App\Models\UserConfirmation;
use Audentio\LaravelBase\Foundation\AbstractModel;
use Audentio\LaravelUserConfirmation\LaravelUserConfirmation;
use Audentio\LaravelUserConfirmation\Models\Interfaces\UserConfirmationModelInterface;
use Audentio\LaravelUserConfirmation\Notifications\ConfirmationNotification;
use Illuminate\Notifications\Notification;

abstract class AbstractConfirmation
{
    protected User $user;

    /** @var UserConfirmationModelInterface|AbstractModel */
    protected UserConfirmationModelInterface $userConfirmation;

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUserConfirmation(): UserConfirmationModelInterface
    {
        return $this->userConfirmation;
    }

    public function getConfirmationUrl(): string
    {
        $url = LaravelUserConfirmation::getBaseConfirmationUrl();

        if (strpos($url, '?') !== false) {
            $url .= '&';
        } else {
            $url .= '?';
        }

        $url .= http_build_query([
            'id' => $this->userConfirmation->id,
            'type' => $this->getConfirmationType(),
            'token' => $this->userConfirmation->token,
        ]);

        return $url;
    }

    public function canSendConfirmation(): bool
    {
        return true;
    }

    public function sendConfirmation(): void
    {
        $this->userConfirmation->generateConfirmationToken();
        $this->userConfirmation->save();

        $this->sendNotification();
    }

    public function confirm(?string $extra = null): bool
    {
        $confirmed = $this->_confirm($extra);
        if ($confirmed) {
            $this->userConfirmation->delete();
            return true;
        }

        return false;
    }

    protected function getNotificationHandler(): Notification
    {
        return new ConfirmationNotification($this);
    }

    protected function sendNotification(): void
    {
        $notification = $this->user->notify($this->getNotificationHandler());
    }

    public abstract function getConfirmationType(): string;
    public abstract function getConfirmationCtaText(): string;
    public abstract function getConfirmationSubject(): string;
    public abstract function getConfirmationDescription(): string;

    protected abstract function _confirm(?string $extra = null): bool;

    public function __construct(User $user, ?UserConfirmation $userConfirmation = null)
    {
        $this->user = $user;

        $handlerClass = get_class($this);

        if ($userConfirmation) {
            if ($userConfirmation->handler_class !== $handlerClass) {
                throw new \RuntimeException('Invalid handler class ' . $userConfirmation->handler_class . ' passed to ' . $handlerClass);
            }

            $this->userConfirmation = $userConfirmation;
        } else {
            $query = UserConfirmation::query();
            $existingConfirmation = $query->where('user_id', $this->user->id)
                ->where('handler_class', $handlerClass)
                ->first();

            $this->userConfirmation = $existingConfirmation ?? new UserConfirmation([
                'user_id' => $this->user->id,
                'handler_class' => $handlerClass,
            ]);
        }
    }
}