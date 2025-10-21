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

    public function testCreateProduct()
    {
        $productRepo = Mockery::mock('ProductRepository');
        $productRepo->shouldReceive('create')
                   ->once()
                   ->with(['name' => 'Test Product', 'price' => 29.99])
                   ->andReturn(['id' => 1, 'name' => 'Test Product']);

        $productService = new ProductService($productRepo);
        $result = $productService->createProduct('Test Product', 29.99);

        $this->assertIsArray($result);
        $this->assertEquals('Test Product', $result['name']);
    }

    public function testUpdateStock()
    {
        $productRepo = Mockery::mock('ProductRepository');
        $productRepo->shouldReceive('updateStock')
                   ->once()
                   ->with(1, 50)
                   ->andReturn(true);

        $productService = new ProductService($productRepo);
        $result = $productService->updateStock(1, 50);

        $this->assertTrue($result);
    }
}
