<?php

namespace Tests\Unit;

use App\Services\CustomerService;
use PHPUnit\Framework\TestCase;
use Mockery;

class CustomerServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testRegisterCustomer()
    {
        $repo = Mockery::mock('CustomerRepository');
        $repo->shouldReceive('create')
            ->once()
            ->with([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890'
            ])
            ->andReturn(true);

        $service = new CustomerService($repo);
        $this->assertTrue($service->register('John Doe', 'john@example.com', '1234567890'));
    }

    public function testFindCustomerByEmail()
    {
        $repo = Mockery::mock('CustomerRepository');
        $repo->shouldReceive('findByEmail')
            ->once()
            ->with('john@example.com')
            ->andReturn(['id' => 1, 'name' => 'John Doe']);

        $service = new CustomerService($repo);
        $result = $service->findByEmail('john@example.com');
        $this->assertEquals('John Doe', $result['name']);
    }

    public function testUpdateCustomerProfile()
    {
        $repo = Mockery::mock('CustomerRepository');
        $repo->shouldReceive('update')
            ->once()
            ->with(1, ['phone' => '0987654321'])
            ->andReturn(true);

        $service = new CustomerService($repo);
        $this->assertTrue($service->updateProfile(1, ['phone' => '0987654321']));
    }
}
