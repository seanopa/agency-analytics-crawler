<?php
namespace App\Message;

use JetBrains\PhpStorm\Pure;

class CrawlWebsiteMessage implements MessageInterface
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

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    #[Pure] public static function getInstanceFromArray(array $content): CrawlWebsiteMessage
    {
        return new self($content['stat_job_id'], $content['link'], $content['maxPagesToCrawl']);
    }
}