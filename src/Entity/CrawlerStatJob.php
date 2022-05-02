<?php

namespace App\Entity;

use App\Repository\CrawlerStatJobRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="aa_crawler_job_stats")
 * @ORM\Entity(repositoryClass=CrawlerStatJobRepository::class)
 */
class CrawlerStatJob
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;


    /**
     * @var Host
     *
     * @ORM\ManyToOne(targetEntity="Link")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="link_id", referencedColumnName="id")
     * })
     */
    protected $initialLink;


    /**
     * @var string|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $total_pages_crawled;

    /**
     * @var string|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $image_total;

    /**
     * @var string|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $internal_link_total;

    /**
     * @var string|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $external_link_total;

    /**
     * @var string|null
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $average_page_load_time;

    /**
     * @var string|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $average_word_count;

    /**
     * @var string|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $average_title_length;

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

    public function getTotalPagesCrawled(): ?int
    {
        return $this->total_pages_crawled;
    }

    public function setTotalPagesCrawled(?int $total_pages_crawled): self
    {
        $this->total_pages_crawled = $total_pages_crawled;

        return $this;
    }

    public function getImageTotal(): ?int
    {
        return $this->image_total;
    }

    public function setImageTotal(?int $image_total): self
    {
        $this->image_total = $image_total;

        return $this;
    }

    public function getInternalLinkTotal(): ?int
    {
        return $this->internal_link_total;
    }

    public function setInternalLinkTotal(?int $internal_link_total): self
    {
        $this->internal_link_total = $internal_link_total;

        return $this;
    }

    public function getExternalLinkTotal(): ?int
    {
        return $this->external_link_total;
    }

    public function setExternalLinkTotal(?int $external_link_total): self
    {
        $this->external_link_total = $external_link_total;

        return $this;
    }

    public function getAveragePageLoadTime(): ?float
    {
        return $this->average_page_load_time;
    }

    public function setAveragePageLoadTime(?float $average_page_load_time): self
    {
        $this->average_page_load_time = $average_page_load_time;

        return $this;
    }

    public function getAverageWordCount()
    {
        return $this->average_word_count;
    }

    public function setAverageWordCount($average_word_count): self
    {
        $this->average_word_count = $average_word_count;

        return $this;
    }

    public function getAverageTitleLength()
    {
        return $this->average_title_length;
    }

    public function setAverageTitleLength($average_title_length): self
    {
        $this->average_title_length = $average_title_length;

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

    public function getInitialLink(): ?Link
    {
        return $this->initialLink;
    }

    public function setInitialLink(?Link $initialLink): self
    {
        $this->initialLink = $initialLink;

        return $this;
    }
}