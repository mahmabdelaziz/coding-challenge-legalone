<?php

namespace App\Tests\Domain\ServiceLog\Service;

use App\Domain\ServiceLog\Http\Request\CountLogsRequest;
use App\Domain\ServiceLog\Repository\ServiceLogRepository;
use App\Domain\ServiceLog\Service\LogAnalyticsService;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class LogAnalyticsServiceTest extends TestCase
{
    public function testCountWithValidParameters(): void
    {
        $serviceLogRepositoryMock = $this->createMock(ServiceLogRepository::class);

        $serviceNames = ['service1', 'service2'];
        $startDateStr = '2023-01-01T00:00:00Z';
        $endDateStr = '2023-01-31T23:59:59Z';
        $statusCode = 200;

        $startDate = DateTimeImmutable::createFromFormat(CountLogsRequest::ISO_8601_FORMAT, $startDateStr);
        $endDate = DateTimeImmutable::createFromFormat(CountLogsRequest::ISO_8601_FORMAT, $endDateStr);

        $serviceLogRepositoryMock->expects($this->once())
            ->method('getCountBy')
            ->with($serviceNames, $startDate, $endDate, $statusCode)
            ->willReturn(10);

        $logAnalyticsService = new LogAnalyticsService($serviceLogRepositoryMock);

        $count = $logAnalyticsService->count($serviceNames, $startDateStr, $endDateStr, $statusCode);

        $this->assertEquals(10, $count);
    }

    public function testCountWithNullDates(): void
    {
        $serviceLogRepositoryMock = $this->createMock(ServiceLogRepository::class);

        $serviceNames = ['service1', 'service2'];
        $startDateStr = null;
        $endDateStr = null;
        $statusCode = 200;

        $serviceLogRepositoryMock->expects($this->once())
            ->method('getCountBy')
            ->with($serviceNames, null, null, $statusCode)
            ->willReturn(5);

        $logAnalyticsService = new LogAnalyticsService($serviceLogRepositoryMock);

        $count = $logAnalyticsService->count($serviceNames, $startDateStr, $endDateStr, $statusCode);

        $this->assertEquals(5, $count);
    }

    public function testCountWithInvalidDateFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date format');

        $serviceLogRepositoryMock = $this->createMock(ServiceLogRepository::class);

        $serviceNames = ['service1', 'service2'];
        $startDateStr = 'invalid-date';
        $endDateStr = 'invalid-date';
        $statusCode = 200;

        $logAnalyticsService = new LogAnalyticsService($serviceLogRepositoryMock);

        $logAnalyticsService->count($serviceNames, $startDateStr, $endDateStr, $statusCode);
    }
}
