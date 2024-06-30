<?php

namespace App\Tests\Domain\ServiceLog\DTO;

use App\Domain\ServiceLog\DTO\LogLineDTO;
use PHPUnit\Framework\TestCase;

class LogLineDTOTest extends TestCase
{
    public function testGetters(): void
    {
        $service = 'test_service';
        $requestedAt = new \DateTimeImmutable('2023-06-27T12:34:56Z');
        $request = 'test_request';
        $statusCode = 200;

        $logLineDTO = new LogLineDTO($service, $requestedAt, $request, $statusCode);

        $this->assertEquals($service, $logLineDTO->getService());
        $this->assertEquals($requestedAt, $logLineDTO->getRequestedAt());
        $this->assertEquals($request, $logLineDTO->getRequest());
        $this->assertEquals($statusCode, $logLineDTO->getStatusCode());
    }
}
