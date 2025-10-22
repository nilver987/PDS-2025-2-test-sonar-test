<?php

namespace Tests\Unit;

use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use Mockery;

class UserServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testCreateUser()
    {
        $userRepo = Mockery::mock('UserRepository');
        $userRepo->shouldReceive('create')
                ->once()
                ->with(['name' => 'John', 'email' => 'john@example.com'])
                ->andReturn(true);

        $userService = new UserService($userRepo);
        $result = $userService->createUser('John', 'john@example.com');
        
        $this->assertTrue($result);
    }
}
