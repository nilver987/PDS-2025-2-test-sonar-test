<?php

namespace App\Services;

class CategoryService
{
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function create($name, $description)
    {
        return $this->repository->create(['name' => $name, 'description' => $description]);
    }

    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
