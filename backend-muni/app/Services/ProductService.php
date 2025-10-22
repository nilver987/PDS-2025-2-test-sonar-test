<?php

namespace App\Services;

class ProductService
{
    private $productRepo;

    public function __construct($productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function getProduct($id)
    {
        return $this->productRepo->find($id);
    }
}
