<?php

namespace App\Tests\Domain\ServiceLog\Http\Request;

use App\Domain\ServiceLog\Http\Request\CountLogsRequest;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class CountLogsRequestTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping() // Use enableAttributeMapping for attributes
            ->getValidator();
    }

    public function testValidRequest(): void
    {
        $request = new CountLogsRequest(
            ['service1', 'service2'],
            '2023-01-01T00:00:00+00:00',
            '2023-01-31T23:59:59+00:00',
            200
        );

        $violations = $this->validator->validate($request);

        $this->assertCount(0, $violations);
        $this->assertEquals(['service1', 'service2'], $request->getServiceNames());
        $this->assertEquals('2023-01-01T00:00:00+00:00', $request->getStartDate());
        $this->assertEquals('2023-01-31T23:59:59+00:00', $request->getEndDate());
        $this->assertEquals(200, $request->getStatusCode());
    }

    public function testInvalidDateFormat(): void
    {
        $request = new CountLogsRequest(
            ['service1'],
            'invalid_date',
            '2023-01-31T23:59:59+00:00',
            200
        );

        $violations = $this->validator->validate($request);

        $this->assertGreaterThan(0, $violations->count());
        $this->assertEquals('This value is not a valid datetime.', $violations[0]->getMessage());
    }

    public function testInvalidStatusCode(): void
    {
        $request = new CountLogsRequest(
            ['service1'],
            '2023-01-01T00:00:00+00:00',
            '2023-01-31T23:59:59+00:00',
            600
        );

        $violations = $this->validator->validate($request);

        $this->assertGreaterThan(0, $violations->count());
        $this->assertEquals('This value should be 599 or less.', $violations[0]->getMessage());
    }

    public function testValidRequestWithEmptyFields(): void
    {
        $request = new CountLogsRequest(
            [],
            null,
            null,
            null
        );

        $violations = $this->validator->validate($request);

        $this->assertCount(0, $violations);
        $this->assertEquals([], $request->getServiceNames());
        $this->assertNull($request->getStartDate());
        $this->assertNull($request->getEndDate());
        $this->assertNull($request->getStatusCode());
    }
}
