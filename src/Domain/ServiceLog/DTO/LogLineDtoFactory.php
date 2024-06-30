<?php

namespace App\Domain\ServiceLog\DTO;

use DateTimeImmutable;

class LogLineDtoFactory
{
    public function createFromLineArray(array $line): LogLineDTO
    {
        $requestedAt = DateTimeImmutable::createFromFormat('d/M/Y:H:i:s O', $line['requested_at']);
        if ($requestedAt === false) {
            throw new \InvalidArgumentException("Invalid datetime format");
        }
        return new LogLineDTO(
            $line['service'],
            $requestedAt,
            $line['request'],
            $line['response_code']
        );
    }
}
