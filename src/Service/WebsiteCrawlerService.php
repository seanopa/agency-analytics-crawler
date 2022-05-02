<?php
namespace App\Service;

use App\Entity\Host;
use App\Http\HttpException;
use App\Http\WebsiteCrawler;
use App\Repository\CrawlerStatJobRepository;
use App\Repository\HostRepository;
use App\Repository\LinkCrawlerTrackingRepository;
use App\Repository\LinkRepository;
use App\Type\CrawlerJobSummary;
use Psr\Log\LoggerInterface;

/**
 * Class WebsiteCrawlerService
 * @package App\Service
 */
class WebsiteCrawlerService
{
    private Host $host;

    private LoggerInterface $logger;
    private CrawlerStatJobRepository $crawlerStatJobRepository;
    private HostRepository $hostRepository;
    private LinkRepository $linkRepository;
    private LinkCrawlerTrackingRepository $crawlerTrackingRepository;
    private WebsiteCrawler $websiteCrawler;
    private ?\App\Entity\CrawlerStatJob $crawlerStatJob;
    private string $initialLink;
    private ?int $maxPagesToVisit;
    private ?\App\Entity\Link $initialLinkEntity;

    /**
     * @param LoggerInterface $logger
     * @param CrawlerStatJobRepository $crawlerStatJobRepository
     * @param HostRepository $hostRepository
     * @param LinkRepository $linkRepository
     * @param LinkCrawlerTrackingRepository $crawlerTrackingRepository
     */
    public function __construct(LoggerInterface $logger, CrawlerStatJobRepository $crawlerStatJobRepository, HostRepository $hostRepository, LinkRepository $linkRepository, LinkCrawlerTrackingRepository $crawlerTrackingRepository)
    {
        $this->logger = $logger;
        $this->crawlerStatJobRepository = $crawlerStatJobRepository;
        $this->hostRepository = $hostRepository;
        $this->linkRepository = $linkRepository;
        $this->crawlerTrackingRepository = $crawlerTrackingRepository;
    }

    /**
     * @return \App\Entity\CrawlerStatJob
     */
    public function createNewStatJob(): \App\Entity\CrawlerStatJob
    {
        $this->crawlerStatJob = $this->crawlerStatJobRepository->create();
        return $this->crawlerStatJob;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setCrawlerJob($id): static
    {
        if (empty($this->crawlerStatJob)) {
            $this->crawlerStatJob = $this->crawlerStatJobRepository->find($id);
        }
        return $this;
    }

    public function getCrawlStatJobSummary($id): ?CrawlerJobSummary
    {
        $this->setCrawlerJob($id);

        if (empty($this->crawlerStatJob)) {
            return null;
        }

        $pagesCrawled = $this->crawlerTrackingRepository->findBy(['crawlerJobStat' => $this->crawlerStatJob]);

        $summary = new CrawlerJobSummary($this->crawlerStatJob, $pagesCrawled);

        return $summary;
    }

    /**
     * @param string $link
     * @return $this
     */
    public function setLink(string $link): static
    {
        $this->initialLink = $link;
        return $this;
    }

    /**
     * @param int|null $maxPagesToVisit
     * @return $this
     */
    public function setMaxPagesToVisit(?int $maxPagesToVisit): static
    {
        $this->maxPagesToVisit = $maxPagesToVisit;
        return $this;
    }

    /**
     * Number of pages crawled
     * Average page load in seconds
     * @return void
     */
    public function run()
    {
        $this->init();

        $url = $this->initialLink;

        do {
            $this->logger->debug(sprintf('%s: Started crawling url: %s', __METHOD__, $url));

            $linkEntity = $this->getLinkEntityForUrl($url);

            $linkCrawlEntity = $this->crawlerTrackingRepository->start($linkEntity, $this->crawlerStatJob);

            try {
                $pageStats = $this->websiteCrawler->statPage($url);

                $this->linkRepository->updateTitle($linkEntity, $pageStats->getPageTitle());

                $this->crawlerTrackingRepository->complete($linkCrawlEntity, $pageStats);


                $this->logger->debug(sprintf('%s: Completed crawling url: %s', __METHOD__, $url));

                $url = $this->websiteCrawler->getUnvisitedPage();

            } catch(HttpException $e) {
                $this->crawlerTrackingRepository->fail($linkCrawlEntity, $e);
                break;
            }

        } while($this->websiteCrawler->hasUnvisitedPages() && !empty($url));

        $websiteStats = $this->websiteCrawler->getWebsiteStats();

        $this->crawlerStatJobRepository->update($this->crawlerStatJob, $websiteStats);
    }

    /**
     * @return void
     */
    private function init()
    {
        if (empty($this->initialLink)) {
            throw new \RuntimeException("Initial url is required");
        }

        if (empty($this->host)) {

           $hostname = parse_url($this->initialLink, PHP_URL_HOST);
           $scheme = parse_url($this->initialLink, PHP_URL_SCHEME);

           $host = $this->hostRepository->findOneBy(['name' => $hostname]);

           if (empty($host)) {
               $host = $this->hostRepository->create($hostname);
           }

           $this->host = $host;

            $this->initialLinkEntity = $this->getLinkEntityForUrl($this->initialLink);

            $this->crawlerStatJobRepository->start($this->crawlerStatJob, $this->initialLinkEntity);

            $this->websiteCrawler = new WebsiteCrawler($this->logger, $scheme, $hostname, $this->maxPagesToVisit);
        }
    }

    /**
     * @param $url
     * @return \App\Entity\Link|null
     */
    private function getLinkEntityForUrl($url): ?\App\Entity\Link
    {
        $linkEntity = $this->linkRepository->findOneBy(['host' => $this->host, 'url' => $url]);

        if (empty($linkEntity)) {
            $linkEntity = $this->linkRepository->create($this->host, $url);
        }

        return $linkEntity;
    }
}