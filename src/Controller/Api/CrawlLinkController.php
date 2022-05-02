<?php
namespace App\Controller\Api;

use App\Http\Response;
use App\Message\CrawlWebsiteMessage;
use App\Service\WebsiteCrawlerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class CrawlLinkController
 * @package App\Controller\Api
 */
class CrawlLinkController extends AbstractController
{
    /**
     * @param Request $request
     * @param WebsiteCrawlerService $websiteCrawlerService
     * @param MessageBusInterface $bus
     * @return JsonResponse
     */
    public function __invoke(Request $request, WebsiteCrawlerService $websiteCrawlerService, MessageBusInterface $bus)
    {
        $url = $request->get('url');

        $maxPagesToCrawl = $request->get('max_pages');

        $newCrawlerJob = $websiteCrawlerService->createNewStatJob();

        Response::flush(new JsonResponse(['job_id' => $newCrawlerJob->getId()]));

        $bus->dispatch(new CrawlWebsiteMessage($newCrawlerJob->getId(), $url, $maxPagesToCrawl));

        exit(0);
    }
}