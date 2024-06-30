<?php

namespace App\Tests\Domain\ServiceLog\Http\Controller;

use App\Domain\ServiceLog\Http\Controller\AnalyticsController;
use App\Domain\ServiceLog\Http\Request\CountLogsRequest;
use App\Domain\ServiceLog\Http\Response\CountLogsErrorResponse;
use App\Domain\ServiceLog\Http\Response\CountLogsResponse;
use App\Domain\ServiceLog\Service\LogAnalyticsService;
use PHPUnit\Framework\TestCase;

class AnalyticsControllerTest extends TestCase
{
    private $logAnalyticsService;
    private $controller;

    protected function setUp(): void
    {
        $this->logAnalyticsService = $this->createMock(LogAnalyticsService::class);
        $this->controller = new AnalyticsController($this->logAnalyticsService);
    }

    public function testCountSuccess()
    {
        $countLogsRequest = new CountLogsRequest();
        $countLogsRequest->serviceNames = ['service1', 'service2'];
        $countLogsRequest->startDate = '2023-01-01';
        $countLogsRequest->endDate = '2023-01-31';
        $countLogsRequest->statusCode = 200;

        $this->logAnalyticsService
            ->expects($this->once())
            ->method('count')
            ->with(['service1', 'service2'], '2023-01-01', '2023-01-31', 200)
            ->willReturn(100);

        $response = $this->controller->count($countLogsRequest);

        $this->assertInstanceOf(CountLogsResponse::class, $response);
        $this->assertEquals(100, json_decode($response->getContent())->counter);
    }

    public function testCountException()
    {
        $countLogsRequest = new CountLogsRequest();
        $countLogsRequest->serviceNames = ['service1', 'service2'];
        $countLogsRequest->startDate = '2023-01-01';
        $countLogsRequest->endDate = '2023-01-31';
        $countLogsRequest->statusCode = 200;

        $this->logAnalyticsService
            ->expects($this->once())
            ->method('count')
            ->with(['service1', 'service2'], '2023-01-01', '2023-01-31', 200)
            ->willThrowException(new \Exception('An error occurred'));

        $response = $this->controller->count($countLogsRequest);

        $this->assertInstanceOf(CountLogsErrorResponse::class, $response);
        $this->assertEquals('An error occurred', json_decode($response->getContent())->exception);
    }
}
