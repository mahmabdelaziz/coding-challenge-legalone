<?php

namespace App\Domain\ServiceLog\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class CountLogsErrorResponse extends JsonResponse
{
    public function __construct(\Exception $e)
    {

        parent::__construct(['error'=> 'Count Logs request error', 'exception'=> $e->getMessage(), 'trace'=>$e->getTraceAsString()], 400);
    }


}
