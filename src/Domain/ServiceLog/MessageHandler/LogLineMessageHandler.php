<?php

namespace App\Domain\ServiceLog\MessageHandler;

use App\Domain\ServiceLog\Message\LogLineMessage;
use App\Domain\ServiceLog\Service\LogLineHandlerService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LogLineMessageHandler
{
    public function __construct(private readonly LogLineHandlerService $logLineHandlerService)
    {
    }

    public function __invoke(LogLineMessage $logLineMessage)
    {
        $this->logLineHandlerService->handle($logLineMessage->getLogLineDTO());
    }
}
