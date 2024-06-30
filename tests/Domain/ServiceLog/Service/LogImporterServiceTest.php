<?php

namespace App\Tests\Domain\ServiceLog\Service;

use App\Domain\ServiceLog\DTO\LogLineDTO;
use App\Domain\ServiceLog\Message\LogLineMessage;
use App\Domain\ServiceLog\Service\LogImportService;
use App\Domain\ServiceLog\Source\SourceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class LogImporterServiceTest extends TestCase
{
    public function testHandleDispatchesMessages()
    {
        $logSourceMock = $this->createMock(SourceInterface::class);

        $logLine1 = $this->createMock(LogLineDTO::class);
        $logLine2 = $this->createMock(LogLineDTO::class);

        $iterator = new \ArrayIterator([$logLine1, $logLine2]);

        $logSourceMock->method('getLogLines')
            ->willReturn($iterator);

        $messageBusMock = $this->createMock(MessageBusInterface::class);

        $dispatchedMessages = [];

        $messageBusMock->method('dispatch')
            ->willReturnCallback(function ($message) use (&$dispatchedMessages) {
                $dispatchedMessages[] = $message;
                return new Envelope($message);
            });

        $service = new LogImportService($logSourceMock, $messageBusMock);

        $service->handle();

        $this->assertCount(2, $dispatchedMessages);
        $this->assertEquals(new LogLineMessage($logLine1), $dispatchedMessages[0]);
        $this->assertEquals(new LogLineMessage($logLine2), $dispatchedMessages[1]);

    }

    public function testHandleIgnoresInvalidLines()
    {
        $logSourceMock = $this->createMock(SourceInterface::class);

        $logLineValid = $this->createMock(LogLineDTO::class);
        $logLineInvalid = new \stdClass(); // Some invalid line not instanceof LogLineDTO

        $iterator = new \ArrayIterator([$logLineValid, $logLineInvalid]);

        $logSourceMock->method('getLogLines')
            ->willReturn($iterator);

        $messageBusMock = $this->createMock(MessageBusInterface::class);

        $dispatchedMessages = [];

        $messageBusMock->method('dispatch')
            ->willReturnCallback(function ($message) use (&$dispatchedMessages) {
                $dispatchedMessages[] = $message;
                return new Envelope($message);
            });

        $service = new LogImportService($logSourceMock, $messageBusMock);

        $service->handle();

        $this->assertCount(1, $dispatchedMessages);
        $this->assertEquals(new LogLineMessage($logLineValid), $dispatchedMessages[0]);
    }
}
