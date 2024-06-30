<?php

namespace App\Domain\ServiceLog\Service;

use App\Domain\ServiceLog\Http\Request\CountLogsRequest;
use App\Domain\ServiceLog\Repository\ServiceLogRepository;
use DateTimeImmutable;
use Webmozart\Assert\Assert;

class LogAnalyticsService
{
    public function __construct(private readonly ServiceLogRepository $serviceLogRepository)
    {
    }

    /**
     * @throws \Exception
     */
    public function count(?array $serviceNames, ?string $startDate, ?string $endDate, ?int $statusCode): int
    {
        $startDate = $this->convertStringToDate($startDate);
        $endDate = $this->convertStringToDate($endDate);

        return
            $this->serviceLogRepository->getCountBy(
                $serviceNames,
                $startDate,
                $endDate,
                $statusCode
            );
    }

    private function convertStringToDate(?string $date): ?DateTimeImmutable
    {
        if (!empty($date)){
            $convertedDate = DateTimeImmutable::createFromFormat(CountLogsRequest::ISO_8601_FORMAT, $date);
            Assert::notFalse($convertedDate, sprintf('Invalid date format: %s', $date));
            return $convertedDate;
        }
        return null;
    }
}
