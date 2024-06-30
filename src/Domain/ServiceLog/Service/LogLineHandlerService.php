<?php

namespace App\Domain\ServiceLog\Service;

use App\Domain\ServiceLog\DTO\LogLineDTO;
use App\Domain\ServiceLog\Entity\ServiceLog;
use App\Domain\ServiceLog\Repository\ServiceLogRepository;

class LogLineHandlerService
{
    public function __construct(private readonly ServiceLogRepository $serviceLogRepository)
    {
    }

    public function handle(LogLineDTO $logLineDTO){
        $serviceLog = new ServiceLog();
        $serviceLog->setService($logLineDTO->getService());
        $serviceLog->setRequestedAt($logLineDTO->getRequestedAt());
        $serviceLog->setRequest($logLineDTO->getRequest());
        $serviceLog->setStatusCode($logLineDTO->getStatusCode());
        $this->serviceLogRepository->insert($serviceLog);
    }
}
