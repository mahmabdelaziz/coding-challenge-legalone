<?php

namespace App\Domain\ServiceLog\Service;

use App\Domain\ServiceLog\DTO\LogLineDTO;
use App\Domain\ServiceLog\Message\LogLineMessage;
use App\Domain\ServiceLog\Source\SourceInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class LogImportService
{
    public function __construct(
        private readonly SourceInterface $LogSource,
        private readonly MessageBusInterface $messageBus
    )
    {
    }

    /**
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     */
    public function handle(){
        foreach ($this->LogSource->getLogLines() as $line){
            if (!$line instanceof LogLineDTO){continue;}
            $this->messageBus->dispatch(new LogLineMessage($line));
        }

    }
}
