<?php

namespace App\Tests\Domain\ServiceLog\Message;

use App\Domain\ServiceLog\DTO\LogLineDTO;
use App\Domain\ServiceLog\Message\LogLineMessage;
use PHPUnit\Framework\TestCase;

class LogLineMessageTest extends TestCase
{
    public function testGetLogLineDTO(): void
    {
        $logLineDTO = $this->createMock(LogLineDTO::class);
        $logLineMessage = new LogLineMessage($logLineDTO);

        $this->assertSame($logLineDTO, $logLineMessage->getLogLineDTO());
    }
}
