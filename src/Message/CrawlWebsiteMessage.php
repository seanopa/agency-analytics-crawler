<?php
namespace App\Message;

class CrawlWebsiteMessage implements \JsonSerializable
{
    protected int $stat_job_id;
    protected string $link;
    protected ?int $maxPagesToCrawl;

    public function __construct(int $stat_job_id, string $link, ?int $maxPagesToCrawl)
    {
        $this->stat_job_id = $stat_job_id;
        $this->link = $link;
        $this->maxPagesToCrawl = $maxPagesToCrawl;
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }

    public function getStatJobId(): int
    {
        return $this->stat_job_id;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getMaxPagesToCrawl(): ?int
    {
        return $this->maxPagesToCrawl;
    }
}