<?php
namespace App\Http;

use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;

/**
 * Class Browser
 * @package App\Http
 */
class WebsiteCrawler
{
    private LoggerInterface $logger;

    private string $scheme;

    private string $hostname;

    private WebsiteStat $websiteStat;


    /**
     * @param LoggerInterface $logger
     * @param string $scheme
     * @param string $hostname
     * @param int|null $maxPagesToVisit
     */
    #[Pure] public function __construct(LoggerInterface $logger, string $scheme, string $hostname, ?int $maxPagesToVisit)
    {
        $this->logger = $logger;
        $this->scheme = $scheme;
        $this->hostname = $hostname;
        $this->websiteStat = new WebsiteStat($hostname, $maxPagesToVisit);
    }

    /**
     * @param string $url
     * @return PageStat
     */
    public function statPage(string $url): PageStat
    {
        $client = new HttpClient($url);

        $document = $client->load();

        $this->websiteStat->addVisitedUrl($url);

        $stat = new PageStat($document, $this->scheme, $this->hostname);

        $this->websiteStat->addStats($stat);

        return $stat;
    }

    #[Pure] public function hasUnvisitedPages(): bool
    {
        return $this->websiteStat->hasUnvisitedPages();
    }

    #[Pure] public function getUnvisitedPage(): string|null
    {
        return $this->websiteStat->getUnvisitedPage();
    }

    public function getWebsiteStats(): WebsiteStat
    {
        return $this->websiteStat;
    }
}

