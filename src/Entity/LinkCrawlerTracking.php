<?php

namespace App\Entity;

use App\Repository\LinkCrawlerTrackingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Link
 *
 * @ORM\Table(name="aa_links_crawler_tracking", indexes={@ORM\Index(name="date", columns={"start_date"})})
 * @ORM\Entity(repositoryClass=LinkCrawlerTrackingRepository::class)
 */
class LinkCrawlerTracking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var CrawlerStatJob
     *
     * @ORM\ManyToOne(targetEntity="CrawlerStatJob")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="job_stat_id", referencedColumnName="id")
     * })
     */
    protected $crawlerJobStat;

    /**
     * @var Link
     *
     * @ORM\ManyToOne(targetEntity="Link")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="link_id", referencedColumnName="id")
     * })
     */
    protected $link;

    /**
     * @var string|null
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $is_started;

    /**
     * @var string|null
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $complete_status;

    /**
     * @var string|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $http_status;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $message;

    /**
     * @var string|null
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $page_load_time;

    /**
     * @var string|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $internal_links_count;

    /**
     * @var string|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $external_links_count;

    /**
     * @var string|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $image_count;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="date", nullable=true)
     */
    protected $start_date;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="time", nullable=true)
     */
    protected $start_time;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsStarted(): ?bool
    {
        return $this->is_started;
    }

    public function setIsStarted(bool $is_started): self
    {
        $this->is_started = $is_started;

        return $this;
    }

    public function getCompleteStatus(): ?int
    {
        return $this->complete_status;
    }

    public function setCompleteStatus(int $complete_status): self
    {
        $this->complete_status = $complete_status;

        return $this;
    }

    public function getHttpStatus(): ?int
    {
        return $this->http_status;
    }

    public function setHttpStatus(?int $http_status): self
    {
        $this->http_status = $http_status;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(?\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getLink(): ?Link
    {
        return $this->link;
    }

    public function setLink(?Link $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getPageLoadTime(): ?float
    {
        return $this->page_load_time;
    }

    public function setPageLoadTime(?float $page_load_time): self
    {
        $this->page_load_time = $page_load_time;

        return $this;
    }

    public function getInternalLinksCount(): ?int
    {
        return $this->internal_links_count;
    }

    public function setInternalLinksCount(?int $internal_links_count): self
    {
        $this->internal_links_count = $internal_links_count;

        return $this;
    }

    public function getExternalLinksCount(): ?int
    {
        return $this->external_links_count;
    }

    public function setExternalLinksCount(?int $external_links_count): self
    {
        $this->external_links_count = $external_links_count;

        return $this;
    }

    public function getImageCount(): ?int
    {
        return $this->image_count;
    }

    public function setImageCount(?int $image_count): self
    {
        $this->image_count = $image_count;

        return $this;
    }

    public function getCrawlerJobStat(): ?CrawlerStatJob
    {
        return $this->crawlerJobStat;
    }

    public function setCrawlerJobStat(?CrawlerStatJob $crawlerJobStat): self
    {
        $this->crawlerJobStat = $crawlerJobStat;

        return $this;
    }
}