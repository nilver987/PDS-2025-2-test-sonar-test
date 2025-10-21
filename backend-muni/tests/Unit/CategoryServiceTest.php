<?php

namespace Tests\Unit;

use App\Services\CategoryService;
use PHPUnit\Framework\TestCase;
use Mockery;

class CategoryServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testCreateCategory()
    {
        $repo = Mockery::mock('CategoryRepository');
        $repo->shouldReceive('create')
            ->once()
            ->with(['name' => 'Test', 'description' => 'Test Desc'])
            ->andReturn(true);

        $service = new CategoryService($repo);
        $this->assertTrue($service->create('Test', 'Test Desc'));
    }

    public function testUpdateCategory()
    {
        $repo = Mockery::mock('CategoryRepository');
        $repo->shouldReceive('update')
            ->once()
            ->with(1, ['name' => 'Updated'])
            ->andReturn(true);

        $service = new CategoryService($repo);
        $this->assertTrue($service->update(1, ['name' => 'Updated']));
    }

    public function testDeleteCategory()
    {
        $repo = Mockery::mock('CategoryRepository');
        $repo->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $service = new CategoryService($repo);
        $this->assertTrue($service->delete(1));
    }
}
