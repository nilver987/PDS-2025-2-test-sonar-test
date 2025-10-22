<?php

namespace App\Services;

class OrderService
{
    private $orderRepo;

    public function __construct($orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function createOrder($productId, $quantity)
    {
        return $this->orderRepo->create([
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }
}
