<?php
namespace App\MessageHandler;

use App\Message\CrawlWebsiteMessage;
use App\Service\WebsiteCrawlerService;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CrawlWebsiteMessageHandler implements MessageHandlerInterface
{
    private WebsiteCrawlerService $httpCrawlerService;

    public function __construct(WebsiteCrawlerService $httpCrawlerService)
    {
        $this->httpCrawlerService = $httpCrawlerService;
    }

    #[Pure] public function __invoke(CrawlWebsiteMessage $message)
    {
        $id = $message->getStatJobId();
        $link = $message->getLink();
        $limit = $message->getMaxPagesToCrawl();

        $this->httpCrawlerService
            ->setCrawlerJob($id)
            ->setLink($link)
            ->setMaxPagesToVisit($limit)
            ;

        $this->httpCrawlerService->run();
    }
}