<?php

namespace App\Tests\Domain\ServiceLog\MessageHandler;

use App\Domain\ServiceLog\Message\LogLineMessage;
use App\Domain\ServiceLog\MessageHandler\LogLineMessageHandler;
use App\Domain\ServiceLog\Service\LogLineHandlerService;
use PHPUnit\Framework\TestCase;

class LogLineMessageHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $logLineDTO = $this->createMock(\App\Domain\ServiceLog\DTO\LogLineDTO::class);
        $logLineMessage = new LogLineMessage($logLineDTO);

        $logLineHandlerService = $this->createMock(LogLineHandlerService::class);
        $logLineHandlerService->expects($this->once())
            ->method('handle')
            ->with($logLineDTO);

        $handler = new LogLineMessageHandler($logLineHandlerService);
        $handler($logLineMessage);
    }
}
