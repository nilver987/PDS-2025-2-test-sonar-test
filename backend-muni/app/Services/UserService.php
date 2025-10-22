<?php

namespace App\Services;

class UserService
{
    private $userRepo;

    public function __construct($userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function createUser($name, $email)
    {
        return $this->userRepo->create([
            'name' => $name,
            'email' => $email
        ]);
    }
}
