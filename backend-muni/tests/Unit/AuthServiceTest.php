<?php

namespace Tests\Unit;

use App\Services\AuthService;
use PHPUnit\Framework\TestCase;
use Mockery;

class AuthServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testLoginValidation()
    {
        $authRepo = Mockery::mock('AuthRepository');
        $authRepo->shouldReceive('validateCredentials')
                ->once()
                ->with('user@test.com', 'password123')
                ->andReturn(true);

        $authService = new AuthService($authRepo);
        $result = $authService->login('user@test.com', 'password123');
        
        $this->assertTrue($result);
    }
}
