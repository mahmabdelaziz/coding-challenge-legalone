<?php

namespace App\Tests\Domain\ServiceLog\Service;

use App\Domain\ServiceLog\DTO\LogLineDTO;
use App\Domain\ServiceLog\Entity\ServiceLog;
use App\Domain\ServiceLog\Repository\ServiceLogRepository;
use App\Domain\ServiceLog\Service\LogLineHandlerService;
use PHPUnit\Framework\TestCase;

class LogLineHandlerServiceTest extends TestCase
{
    public function testHandleInsertsServiceLog(): void
    {
        $serviceLogRepositoryMock = $this->createMock(ServiceLogRepository::class);

        $serviceLogRepositoryMock->expects($this->once())
            ->method('insert')
            ->with($this->isInstanceOf(ServiceLog::class));

        $logLineHandlerService = new LogLineHandlerService($serviceLogRepositoryMock);

        $logLineDTO = $this->createMock(LogLineDTO::class);

        $logLineDTO->method('getService')->willReturn('test_service');
        $logLineDTO->method('getRequestedAt')->willReturn(new \DateTimeImmutable('2023-06-27T12:34:56Z'));
        $logLineDTO->method('getRequest')->willReturn('test_request');
        $logLineDTO->method('getStatusCode')->willReturn(200);

        $logLineHandlerService->handle($logLineDTO);
    }
}
