<?php

namespace App\Domain\ServiceLog\Message;

use App\Domain\ServiceLog\DTO\LogLineDTO;

class LogLineMessage
{
    public function __construct(private readonly LogLineDTO $logLineDTO)
    {
    }

    /**
     * @return LogLineDTO
     */
    public function getLogLineDTO(): LogLineDTO
    {
        return $this->logLineDTO;
    }

}
