<?php

namespace App\Type;

use App\Entity\LinkCrawlerTracking;

/**
 * Class PageInfo
 * @package App\Type
 */
class PageInfo extends AbstractResponseType
{
    protected ?int $tracking_id;
    protected ?string $url;
    protected ?string $title;
    protected string $start_date;
    protected string $start_time;
    protected ?int $http_status;
    protected ?string $message;
    protected ?int $unique_image_count;
    protected ?int $unique_internal_links_count;
    protected ?int $unique_external_links_count;
    protected ?int $complete_status;
    protected string $updated_at;

    /**
     * @param LinkCrawlerTracking $tracking
     */
    public function __construct(LinkCrawlerTracking $tracking)
    {
        $this->tracking_id = $tracking->getId();
        $this->url = $tracking->getLink()->getUrl();
        $this->title = $tracking->getLink()->getTitle();
        $this->start_date = $tracking->getStartDate()?->format('Y-m-d');
        $this->start_time = $tracking->getStartTime()?->format('H:i:s');
        $this->http_status = $tracking->getHttpStatus();
        $this->message = $tracking->getMessage();
        $this->unique_image_count = $tracking->getImageCount();
        $this->unique_internal_links_count = $tracking->getInternalLinksCount();
        $this->unique_external_links_count = $tracking->getExternalLinksCount();
        $this->complete_status = $tracking->getCompleteStatus();
        $this->updated_at = $tracking->getUpdatedAt()?->format('Y-m-d H:i:s');
    }
}