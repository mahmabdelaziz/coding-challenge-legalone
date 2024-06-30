<?php

namespace App\Domain\ServiceLog\Http\Controller;

use App\Domain\ServiceLog\Http\Request\CountLogsRequest;
use App\Domain\ServiceLog\Http\Response\CountLogsErrorResponse;
use App\Domain\ServiceLog\Http\Response\CountLogsResponse;
use App\Domain\ServiceLog\Service\LogAnalyticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class AnalyticsController extends AbstractController
{
    public function __construct(private readonly LogAnalyticsService $logAnalyticsService)
    {
    }

    #[Route('/count', name: 'app_analytics')]
    public function count(
        #[MapQueryString] CountLogsRequest $countLogsRequest = new CountLogsRequest()
    ): CountLogsResponse|CountLogsErrorResponse
    {
        try{
            $count = $this->logAnalyticsService->count(
                $countLogsRequest->getServiceNames(),
                $countLogsRequest->startDate,
                $countLogsRequest->endDate,
                $countLogsRequest->statusCode
            );
            return new CountLogsResponse($count);
        }catch (\Exception $exception){
            return new CountLogsErrorResponse($exception);
        }
    }
}
