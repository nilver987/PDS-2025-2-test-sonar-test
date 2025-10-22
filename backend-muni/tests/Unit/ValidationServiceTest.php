<?php

namespace Tests\Unit;

use App\Services\ValidationService;
use PHPUnit\Framework\TestCase;
use Mockery;

class ValidationServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testEmailValidation()
    {
        $validator = new ValidationService();
        $result = $validator->validateEmail('test@example.com');
        
        $this->assertTrue($result);
    }
}
