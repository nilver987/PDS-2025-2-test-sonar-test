<?php

namespace App\Services;

class CustomerService
{
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function register($name, $email, $phone)
    {
        return $this->repository->create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ]);
    }

    public function findByEmail($email)
    {
        return $this->repository->findByEmail($email);
    }

    public function updateProfile($id, $data)
    {
        return $this->repository->update($id, $data);
    }
}
