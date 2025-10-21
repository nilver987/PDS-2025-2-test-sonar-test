<?php
namespace Tests\Unit;

use App\Services\ReportService;
use PHPUnit\Framework\TestCase;
use Mockery;

class ReportServiceTest extends TestCase
{
    protected function tearDown(): void {
        Mockery::close();
    }

    public function testGenerateDailyReport()
    {
        $repo = Mockery::mock('ReportRepository');
        $repo->shouldReceive('getDailyStats')
             ->once()
             ->with('2024-01-01')
             ->andReturn(['total' => 100]);

        $service = new ReportService($repo);
        $this->assertEquals(['total' => 100], $service->generateDailyReport('2024-01-01'));
    }

    public function testGenerateMonthlyReport()
    {
        $repo = Mockery::mock('ReportRepository');
        $repo->shouldReceive('getMonthlyStats')
             ->once()
             ->with(1, 2024)
             ->andReturn(['total' => 3000]);

        $service = new ReportService($repo);
        $this->assertEquals(['total' => 3000], $service->generateMonthlyReport(1, 2024));
    }

    public function testExportToPdf()
    {
        $repo = Mockery::mock('ReportRepository');
        $repo->shouldReceive('exportPdf')
             ->once()
             ->with(['data' => 'test'])
             ->andReturn(true);

        $service = new ReportService($repo);
        $this->assertTrue($service->exportToPdf(['data' => 'test']));
    }

    public function testGetCustomReport()
    {
        $repo = Mockery::mock('ReportRepository');
        $repo->shouldReceive('generateCustom')
             ->once()
             ->with(['type' => 'custom'])
             ->andReturn(['result' => 'custom data']);

        $service = new ReportService($repo);
        $this->assertEquals(['result' => 'custom data'], $service->getCustomReport(['type' => 'custom']));
    }

    public function testScheduleReport()
    {
        $repo = Mockery::mock('ReportRepository');
        $repo->shouldReceive('scheduleGeneration')
             ->once()
             ->with('daily', 'every_morning')
             ->andReturn(true);

        $service = new ReportService($repo);
        $this->assertTrue($service->scheduleReport('daily', 'every_morning'));
    }
}
