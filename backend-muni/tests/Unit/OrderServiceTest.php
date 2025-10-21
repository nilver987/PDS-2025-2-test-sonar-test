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
                 ->with(['product_id' => 1, 'quantity' => 100])
                 ->andReturn(['id' => 1, 'status' => 'pending']);

        $orderService = new OrderService($orderRepo);
        $result = $orderService->createOrder(1, 100);

        $this->assertEquals('pending', $result['status']);
    }

    public function testUpdateOrderStatus()
    {
        $orderRepo = Mockery::mock('OrderRepository');
        $orderRepo->shouldReceive('updateStatus')
                 ->once()
                 ->with(1, 'completed')
                 ->andReturn(true);

        $orderService = new OrderService($orderRepo);
        $result = $orderService->updateStatus(1, 'completed');

        $this->assertTrue($result);
    }
}
