<?php

namespace Tests\Unit;

use App\Services\PaymentService;
use PHPUnit\Framework\TestCase;
use Mockery;

class PaymentServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testProcessPayment()
    {
        $paymentGateway = Mockery::mock('PaymentGateway');
        $paymentGateway->shouldReceive('process')
                      ->once()
                      ->with(100.00, 'USD')
                      ->andReturn(true);

        $paymentService = new PaymentService($paymentGateway);
        $result = $paymentService->processPayment(100.00, 'USD');

        $this->assertTrue($result);
    }
}
