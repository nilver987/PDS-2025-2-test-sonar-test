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

    public function createProduct($name, $price)
    {
        return $this->productRepo->create([
            'name' => $name,
            'price' => $price
        ]);
    }

    public function updateStock($productId, $quantity)
    {
        return $this->productRepo->updateStock($productId, $quantity);
    }
}
