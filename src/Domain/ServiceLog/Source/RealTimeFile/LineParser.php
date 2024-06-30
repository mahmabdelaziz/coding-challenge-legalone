<?php

namespace App\Domain\ServiceLog\Source\RealTimeFile;

class LineParser
{
    public function parse(string $line): array|bool {
        $pattern = '/^([^\s]+)\s+-\s+-\s+\[([^\]]+)\]\s+"([^"]+)"\s+(\d+)$/';
        $lineArray = [];

        if (preg_match($pattern, $this->clean($line), $matches)) {
            $lineArray['service'] = $matches[1]; // Service name
            $lineArray['requested_at'] = $matches[2]; // Timestamp
            $lineArray['request'] = $matches[3]; // Request part
            $lineArray['response_code'] = $matches[4]; // Response code
            return $lineArray;
        }

        return false;
    }

    private function clean(string $line): string
    {
        $line = trim($line, "\xEF\xBB\xBF");
        return trim(preg_replace('/\s\s+/', ' ', $line));
    }
}
