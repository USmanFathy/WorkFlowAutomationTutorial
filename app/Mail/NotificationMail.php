<?php

namespace App\Mail;

class NotificationMail
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
