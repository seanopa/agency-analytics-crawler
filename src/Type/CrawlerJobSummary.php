<?php

namespace App\Type;

use App\Entity\CrawlerStatJob;
use App\Entity\LinkCrawlerTracking;

/**
 * Class CrawlerJobSummary
 * @package App\Type
 */
class CrawlerJobSummary extends AbstractResponseType
{
    protected ?int $job_id;
    protected ?string $start_date;
    protected ?string $start_time;
    protected ?float $average_page_load_time;
    protected ?string $average_title_length;
    protected ?string $average_word_count;
    protected ?int $total_pages_crawled;
    protected ?int $unique_internal_link_total;
    protected ?int $unique_external_link_total;
    protected ?int $unique_image_total;
    protected ?string $start_url;
    protected array $pages = [];

    /**
     * @param CrawlerStatJob $crawlerStatJob
     * @param LinkCrawlerTracking[] $tracking
     */
    public function __construct(CrawlerStatJob $crawlerStatJob, array $tracking)
    {
        $this->job_id = $crawlerStatJob->getId();
        $this->start_date = $crawlerStatJob->getStartDate()?->format('Y-m-d');
        $this->start_time = $crawlerStatJob->getStartTime()?->format('H:i:s');
        $this->average_page_load_time = $crawlerStatJob->getAveragePageLoadTime();
        $this->average_title_length = $crawlerStatJob->getAverageTitleLength();
        $this->average_word_count = $crawlerStatJob->getAverageWordCount();
        $this->total_pages_crawled = $crawlerStatJob->getTotalPagesCrawled();
        $this->unique_internal_link_total = $crawlerStatJob->getInternalLinkTotal();
        $this->unique_external_link_total = $crawlerStatJob->getExternalLinkTotal();
        $this->unique_image_total = $crawlerStatJob->getImageTotal();
        $this->start_url = $crawlerStatJob->getInitialLink()?->getUrl();
        $this->updated_at = $crawlerStatJob->getUpdatedAt()?->format('Y-m-d H:i:s');

        foreach ($tracking as $item) {
            $this->pages[] = new PageInfo($item);
        }
    }
}