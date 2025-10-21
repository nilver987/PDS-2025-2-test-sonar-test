<?php

namespace Tests\Unit;

use App\Services\NotificationService;
use PHPUnit\Framework\TestCase;
use Mockery;

class NotificationServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testSendNotification()
    {
        $notifier = Mockery::mock('Notifier');
        $notifier->shouldReceive('send')
                ->once()
                ->with('user123', 'Test Notification')
                ->andReturn(true);

        $notificationService = new NotificationService($notifier);
        $result = $notificationService->sendNotification('user123', 'Test Notification');

        $this->assertTrue($result);
    }
}
