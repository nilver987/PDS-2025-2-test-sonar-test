<?php

namespace App\Services;

class NotificationService
{
    private $notifier;

    public function __construct($notifier)
    {
        $this->notifier = $notifier;
    }

    public function sendNotification($userId, $message)
    {
        return $this->notifier->send($userId, $message);
    }
}
