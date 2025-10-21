<?php
namespace App\Services;

class ReportService {
    private $repository;

    public function __construct($repository) {
        $this->repository = $repository;
    }

    public function generateDailyReport($date) {
        return $this->repository->getDailyStats($date);
    }

    public function generateMonthlyReport($month, $year) {
        return $this->repository->getMonthlyStats($month, $year);
    }

    public function exportToPdf($reportData) {
        return $this->repository->exportPdf($reportData);
    }

    public function getCustomReport($params) {
        return $this->repository->generateCustom($params);
    }

    public function scheduleReport($reportType, $schedule) {
        return $this->repository->scheduleGeneration($reportType, $schedule);
    }
}
