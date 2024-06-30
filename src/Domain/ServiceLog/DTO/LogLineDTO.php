<?php

namespace App\Domain\ServiceLog\DTO;

class LogLineDTO
{
    public function __construct(
        private readonly string $service,
        private readonly \DateTimeInterface $requestedAt,
        private readonly string $request,
        private readonly int $statusCode
    )
    {
    }

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getRequestedAt(): \DateTimeInterface
    {
        return $this->requestedAt;
    }

    /**
     * @return string
     */
    public function getRequest(): string
    {
        return $this->request;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
