<?php

namespace App\Services;

class AuthService
{
    private $authRepo;

    public function __construct($authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function login($email, $password)
    {
        return $this->authRepo->validateCredentials($email, $password);
    }
}
