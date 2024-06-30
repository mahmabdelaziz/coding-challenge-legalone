<?php

namespace App\Tests\Domain\ServiceLog\Http\Response;

use App\Domain\ServiceLog\Http\Response\CountLogsErrorResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class CountLogsErrorResponseTest extends TestCase
{
    public function testResponseContent(): void
    {
        $exceptionMessage = 'Test exception message';
        $exception = new \Exception($exceptionMessage);
        $response = new CountLogsErrorResponse($exception);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertIsArray($content);
        $this->assertArrayHasKey('error', $content);
        $this->assertArrayHasKey('exception', $content);
        $this->assertArrayHasKey('trace', $content);
        $this->assertEquals('Count Logs request error', $content['error']);
        $this->assertEquals($exceptionMessage, $content['exception']);
        $this->assertNotEmpty($content['trace']);
    }
}
