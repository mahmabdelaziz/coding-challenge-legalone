<?php

namespace App\Domain\ServiceLog\Http\Request;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final class CountLogsRequest
{
    const ISO_8601_FORMAT = 'Y-m-d\TH:i:sO';
    public function __construct(
        #[Assert\All(new Assert\Type('string'))]
        public ?array $serviceNames = [],

        #[Assert\DateTime(self::ISO_8601_FORMAT)] // iso 8601
        public ?string $startDate = null,

        #[Assert\DateTime(self::ISO_8601_FORMAT)] // iso 8601
        public ?string $endDate = null,

        #[Assert\Range(max: 599)]
        public ?int $statusCode = null,
    ) {
    }

    /**
     * @return array|null
     */
    public function getServiceNames(): ?array
    {
        return $this->serviceNames;
    }

    /**
     * @return string|null
     */
    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    /**
     * @return string|null
     */
    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
}
