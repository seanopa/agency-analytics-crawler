<?php
namespace App\Controller\Api;

use App\Service\WebsiteCrawlerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class GetCrawlerStatJobController
 * @package App\Controller\Api
 */
class GetCrawlerStatJobController extends AbstractController
{
    /**
     * @param Request $request
     * @param WebsiteCrawlerService $websiteCrawlerService
     * @param $job_id
     * @return JsonResponse
     */
    public function __invoke(Request $request, WebsiteCrawlerService $websiteCrawlerService, $job_id)
    {
        $job = $websiteCrawlerService->getCrawlStatJobSummary($job_id);

        if (empty($job)) {
            throw new HttpException(404, 'No such job exists');
        }

        return new JsonResponse($job);
    }
}