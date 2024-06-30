<?php

namespace App\Domain\ServiceLog\Http\Response;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\JsonResponse;

class CountLogsResponse extends JsonResponse
{
    public function __construct(int $count)
    {
        $data = $this->prepareResponseBody($count);
        parent::__construct($data, 200);
    }

    #[ArrayShape(['counter' => "int"])] private function prepareResponseBody(int $count): array
    {
        return [
            'counter' => $count
        ];
    }
}
