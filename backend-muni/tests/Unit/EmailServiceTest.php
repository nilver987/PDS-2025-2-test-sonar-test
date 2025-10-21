<?php

namespace Tests\Unit;

use App\Services\EmailService;
use PHPUnit\Framework\TestCase;
use Mockery;

class EmailServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testSendEmail()
    {
        $mailer = Mockery::mock('Mailer');
        $mailer->shouldReceive('send')
               ->once()
               ->with('test@example.com', 'Test Subject', 'Test Content')
               ->andReturn(true);

        $emailService = new EmailService($mailer);
        $result = $emailService->sendEmail('test@example.com', 'Test Subject', 'Test Content');

        $this->assertTrue($result);
    }
}
