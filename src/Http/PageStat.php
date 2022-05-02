<?php
namespace App\Http;

use App\Util\WordProcessor;
use JetBrains\PhpStorm\Pure;

class PageStat
{
    private CurlDocument $document;
    private CurlInfo $info;
    private string $scheme;
    private string $hostname;
    private bool $count_subdomains_as_internal;

    private string $page_title;
    private array $internal_links = [];
    private array $fragments = [];
    private array $external_links = [];
    private array $images;
    private WordProcessor $wordProcessor;

    /**
     * @param CurlDocument $document
     * @param string $scheme
     * @param string $hostname
     * @param bool $count_subdomains_as_internal
     */
    public function __construct(CurlDocument $document, string $scheme, string $hostname, bool $count_subdomains_as_internal = false)
    {
        $this->document = $document;
        $this->info = $document->getCurlInfo();
        $this->scheme = $scheme;
        $this->hostname = $hostname;
        $this->count_subdomains_as_internal = $count_subdomains_as_internal;
        $this->build();
    }

    /**
     * @return void
     */
    private function build()
    {
        $this->setPageTitle();

        $links = $this->getAttributeFromElementsWithTagName('a', 'href');

        $this->processLinks($links);

        $images = $this->getAttributeFromElementsWithTagName('img', 'src');

        $this->processImages($images);

        $rawHtml = $this->document->getRawHtml();

        $this->wordProcessor = new WordProcessor($rawHtml, true);

    }

    /**
     * @return void
     */
    private function setPageTitle()
    {
        $elements = $this->document->getElementsByTagName('title');

        if (!empty($elements)) {
            $this->page_title = $elements[0]->nodeValue;
        }
    }

    /**
     * @param $tagName
     * @param $attribute
     * @return array
     */
    private function getAttributeFromElementsWithTagName($tagName, $attribute): array
    {
        $attributes = [];

        $elements = $this->document->getElementsByTagName($tagName);

        foreach ($elements as $element) {
            $attributes[] = $element->getAttribute($attribute);
        }

        return $attributes;
    }

    /**
     * @param array $links
     * @return void
     */
    private function processLinks(array $links)
    {
        $hostname = $this->count_subdomains_as_internal ? strrev($this->hostname) : $this->hostname; // If we have example.com and subdomains like admin.example.com must be considered then this will pass

        foreach ($links as $link) {

            if (str_starts_with($link, '/')) {
                $link = sprintf('%s://%s%s', $this->scheme, $this->hostname, $link);
                $this->internal_links[$link] = 1;
                continue;
            }

            if (str_starts_with($link, '#')) {
                $link = sprintf('%s://%s/%s', $this->scheme, $this->hostname, $link);
                $this->fragments[$link] = 1;
                continue;
            }


            $link_hostname = parse_url($link, PHP_URL_HOST);
            $link_hostname = $this->count_subdomains_as_internal ? strrev($link_hostname) : $link_hostname;

            if (str_starts_with($link_hostname, $hostname)) {
                empty($this->internal_links[$link]) && $this->internal_links[$link] = 1;
            } else {
                empty($this->external_links[$link]) && $this->external_links[$link] = 1;
            }
        }
    }

    /**
     * @param array $images
     * @return void
     */
    private function processImages(array $images)
    {
        foreach ($images as $image) {
            empty($this->images[$image]) && $this->images[$image] = 1;
        }
    }


    public function getPageTitle(): string
    {
        return $this->page_title;
    }

    public function getInternalLinks(): array
    {
        return $this->internal_links;
    }

    public function getFragments(): array
    {
        return $this->fragments;
    }

    public function getExternalLinks(): array
    {
        return $this->external_links;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    #[Pure] public function getWordCount(): float|int
    {
        return $this->wordProcessor->getTotalWordsFound();
    }

    public function getInfo(): CurlInfo
    {
        return $this->info;
    }
}