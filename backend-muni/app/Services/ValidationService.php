<?php

namespace App\Services;

class ValidationService
{
    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
