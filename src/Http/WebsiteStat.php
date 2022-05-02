<?php
namespace App\Http;

/**
 * Class WebsiteStat
 * @package App\Http
 */
class WebsiteStat
{
    private string $hostname;

    private int $maxPagesToVisit;

    private int $numPagesVisited = 0;

    private array $visited = [];

    private array $internal_links = [];

    private array $external_links = [];

    private array $images = [];

    private array $page_load_times = [];

    private array $page_title_size = [];
    
    private array $word_count_per_page = [];

    /**
     * @param string $hostname
     * @param int|null $maxPagesToVisit
     */
    public function __construct(string $hostname, ?int $maxPagesToVisit)
    {
        $this->hostname = $hostname;
        $this->maxPagesToVisit = $maxPagesToVisit;
    }

    /**
     * @param string $url
     * @return void
     */
    public function addVisitedUrl(string $url)
    {
        $this->visited[$url] = true;
        $this->numPagesVisited++;
    }

    /**
     * @return bool
     */
    public function hasUnvisitedPages(): bool
    {
        if ($this->maxPagesToVisit > 0 && $this->numPagesVisited < $this->maxPagesToVisit) {
            if (count($this->visited) < $this->maxPagesToVisit) {
                return true;
            }
        } elseif ($this->maxPagesToVisit == -1) {
            return !empty(array_diff_key($this->internal_links, $this->visited));
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getUnvisitedPage(): string|null
    {
        $unvisited = array_diff_key($this->internal_links, $this->visited);

        return array_key_first($unvisited);
    }

    /**
     * @param PageStat $stat
     * @return void
     */
    public function addStats(PageStat $stat)
    {
        $this->addInternalLinks($stat->getInternalLinks());
        $this->addExternalLinks($stat->getExternalLinks());
        $this->addImages($stat->getImages());

        $this->page_load_times[] = $stat->getInfo()->getTotalTime();

        $title = $stat->getPageTitle();
        $this->page_title_size[] = !empty($title)? strlen($title) : 0;
        
        $this->word_count_per_page[] = $stat->getWordCount();
    }


    /**
     * @param $links
     * @return void
     */
    private function addInternalLinks($links)
    {
        $new_links = array_diff_key($links, $this->internal_links);

        if (!empty($new_links)) {
            $this->internal_links = array_merge($this->internal_links, $new_links);
        }
    }

    /**
     * @param $links
     * @return void
     */
    private function addExternalLinks($links)
    {
        $new_links = array_diff_key($links, $this->external_links);

        if (!empty($new_links)) {
            $this->external_links = array_merge($this->external_links, $new_links);
        }
    }

    /**
     * @param $images
     * @return void
     */
    private function addImages($images)
    {
        $new_images = array_diff_key($images, $this->images);

        if (!empty($new_images)) {
            $this->images = array_merge($this->images, $new_images);
        }
    }

    /**
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }

    /**
     * @return int|null
     */
    public function getMaxPagesToVisit(): ?int
    {
        return $this->maxPagesToVisit;
    }

    /**
     * @return int
     */
    public function getNumPagesVisited(): int
    {
        return $this->numPagesVisited;
    }

    /**
     * @return array
     */
    public function getVisited(): array
    {
        return $this->visited;
    }

    /**
     * @return array
     */
    public function getInternalLinks(): array
    {
        return $this->internal_links;
    }

    /**
     * @return array
     */
    public function getExternalLinks(): array
    {
        return $this->external_links;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return int
     */
    public function getImagesTotal(): int
    {
        return count($this->images);
    }

    /**
     * @return int
     */
    public function getInternalLInksTotal(): int
    {
        return count($this->internal_links);
    }

    /**
     * @return int
     */
    public function getExternalLinksTotal(): int
    {
        return count($this->external_links);
    }

    /**
     * @return float|int
     */
    public function getAveragePageLoadTime()
    {
        return $this->array_average($this->page_load_times);
    }

    /**
     * @return float|int
     */
    public function getAverageWordCount()
    {
        return $this->array_average($this->word_count_per_page);
    }

    /**
     * @return float|int
     */
    public function getAveragePageTitleLength()
    {
        return $this->array_average($this->page_title_size);
    }

    /**
     * @param array $array
     * @return float|int
     */
    private function array_average(array $array): float|int
    {
        if (empty($array)) return 0;

        $sum = array_sum($array);

        return $sum / count($array);
    }
}