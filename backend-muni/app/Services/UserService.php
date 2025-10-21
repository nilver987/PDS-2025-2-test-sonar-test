<?php

namespace App\Services;

class UserService
{
    private $userRepo;

    public function __construct($userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register($name, $email)
    {
        return $this->userRepo->create([
            'name' => $name,
            'email' => $email
        ]);
    }

    public function authenticate($email, $password)
    {
        return $this->userRepo->findByCredentials($email, $password);
    }

    public function createUser($name, $email)
    {
        return $this->userRepo->create([
            'name' => $name,
            'email' => $email
        ]);
    }
}
