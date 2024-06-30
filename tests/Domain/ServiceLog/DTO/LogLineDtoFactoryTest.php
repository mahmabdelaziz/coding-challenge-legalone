<?php

namespace App\Tests\Domain\ServiceLog\DTO;

use App\Domain\ServiceLog\DTO\LogLineDTO;
use App\Domain\ServiceLog\DTO\LogLineDtoFactory;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class LogLineDtoFactoryTest extends TestCase
{
    private LogLineDtoFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new LogLineDtoFactory();
    }

    public function testCreateFromLineArray(): void
    {
        $line = [
            'service' => 'test_service',
            'requested_at' => '27/Jun/2023:12:34:56 +0000',
            'request' => 'test_request',
            'response_code' => 200
        ];

        $logLineDTO = $this->factory->createFromLineArray($line);

        $this->assertInstanceOf(LogLineDTO::class, $logLineDTO);
        $this->assertEquals('test_service', $logLineDTO->getService());
        $this->assertEquals(new DateTimeImmutable('2023-06-27 12:34:56'), $logLineDTO->getRequestedAt());
        $this->assertEquals('test_request', $logLineDTO->getRequest());
        $this->assertEquals(200, $logLineDTO->getStatusCode());
    }

    public function testCreateFromLineArrayWithInvalidDateFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid datetime format');

        $line = [
            'service' => 'test_service',
            'requested_at' => 'invalid_date_format',
            'request' => 'test_request',
            'response_code' => 200
        ];

        $this->factory->createFromLineArray($line);
    }
}
