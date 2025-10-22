<?php

namespace Tests\Unit;

use App\Services\ProductService;
use PHPUnit\Framework\TestCase;
use Mockery;

class ProductServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testGetProductDetails()
    {
        $productRepo = Mockery::mock('ProductRepository');
        $productRepo->shouldReceive('find')
                   ->once()
                   ->with(1)
                   ->andReturn(['id' => 1, 'name' => 'Test Product']);

        $productService = new ProductService($productRepo);
        $result = $productService->getProduct(1);
        
        $this->assertEquals('Test Product', $result['name']);
    }
}
