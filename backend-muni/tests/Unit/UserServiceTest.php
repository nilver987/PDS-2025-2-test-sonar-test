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

    public function testRegisterUser()
    {
        $userRepo = Mockery::mock('UserRepository');
        $userRepo->shouldReceive('create')
                ->once()
                ->with(['name' => 'John Doe', 'email' => 'john@example.com'])
                ->andReturn(true);

        $userService = new UserService($userRepo);
        $result = $userService->register('John Doe', 'john@example.com');

        $this->assertTrue($result);
    }

    public function testAuthenticateUser()
    {
        $userRepo = Mockery::mock('UserRepository');
        $userRepo->shouldReceive('findByCredentials')
                ->once()
                ->with('john@example.com', 'password123')
                ->andReturn(['id' => 1, 'name' => 'John Doe']);

        $userService = new UserService($userRepo);
        $result = $userService->authenticate('john@example.com', 'password123');

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }
}
