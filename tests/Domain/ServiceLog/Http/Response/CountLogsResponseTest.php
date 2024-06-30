<?php

namespace App\Tests\Domain\ServiceLog\Http\Response;

use App\Domain\ServiceLog\Http\Response\CountLogsResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class CountLogsResponseTest extends TestCase
{
    public function testResponseContent(): void
    {
        $count = 5;
        $response = new CountLogsResponse($count);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertIsArray($content);
        $this->assertArrayHasKey('counter', $content);
        $this->assertEquals($count, $content['counter']);
    }
}
