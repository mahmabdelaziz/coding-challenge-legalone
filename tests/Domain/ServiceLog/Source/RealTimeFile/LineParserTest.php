<?php

namespace App\Tests\Domain\ServiceLog\Source\RealTimeFile;

use App\Domain\ServiceLog\Source\RealTimeFile\LineParser;
use PHPUnit\Framework\TestCase;

class LineParserTest extends TestCase
{
    private LineParser $lineParser;

    protected function setUp(): void
    {
        $this->lineParser = new LineParser();
    }

    public function testParseValidLine(): void
    {
        $line = 'INVOICE-SERVICE - - [17/Aug/2018:09:26:53 +0000] "POST /invoices HTTP/1.1" 201';
        $expected = [
            'service' => 'INVOICE-SERVICE',
            'requested_at' => '17/Aug/2018:09:26:53 +0000',
            'request' => 'POST /invoices HTTP/1.1',
            'response_code' => '201',
        ];

        $result = $this->lineParser->parse($line);

        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    public function testParseInvalidLine(): void
    {
        $line = 'invalid line format';
        $result = $this->lineParser->parse($line);

        $this->assertFalse($result);
    }
}
