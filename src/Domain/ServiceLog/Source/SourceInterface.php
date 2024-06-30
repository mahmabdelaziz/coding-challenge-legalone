<?php

namespace App\Domain\ServiceLog\Source;

use App\Domain\ServiceLog\DTO\LogLineDTO;
use Iterator;

interface SourceInterface
{
    /**
     * @return Iterator<LogLineDTO>
     */
    public function getLogLines(): Iterator;
}
