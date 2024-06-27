<?php

namespace App\Notifications;

class BroadcastNotification
{

    private string $message;
    /**
     * @param mixed|string $message
     */
    public function __construct(mixed $message)
    {
        $this->message = $message;
    }
}
