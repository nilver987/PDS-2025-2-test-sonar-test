<?php

namespace App\Services;

class EmailService
{
    private $mailer;

    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($email, $subject, $content)
    {
        return $this->mailer->send($email, $subject, $content);
    }
}
