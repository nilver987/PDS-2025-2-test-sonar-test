<?php

namespace Tests\Unit;

use App\Services\OrderService;
use PHPUnit\Framework\TestCase;
use Mockery;

class OrderServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testCreateOrder()
    {
        $orderRepo = Mockery::mock('OrderRepository');
        $orderRepo->shouldReceive('create')
                 ->once()
                 ->with(['product_id' => 1, 'quantity' => 2])
                 ->andReturn(true);

        $orderService = new OrderService($orderRepo);
        $result = $orderService->createOrder(1, 2);
        
        $this->assertTrue($result);
    }
}
