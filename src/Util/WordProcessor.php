<?php

namespace App\Util;

/**
 * Class WordProcessor
 * @package App\Util
 */
class WordProcessor
{
    private string $content;
    /**
     * @var false|mixed
     */
    private bool $is_html;

    private int $total_unique_words;

    private int|float $total_words_found;
    /**
     * @var int[]|string[]
     */
    private array $words_collection;

    /**
     * @param string $content
     * @param bool $is_html
     */
    public function __construct(string $content, bool $is_html = false)
    {
        $this->content = $content;
        $this->is_html = $is_html;
        $this->process();
    }

    /**
     * @return void
     */
    private function process()
    {
        $content = $this->content;

        if($this->is_html) {
            // Get rid of style, script etc
            $search = [
                '@<![^>]*?>@si',  // Strip doctype
                '@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                '@<head>.*?</head>@siU',            // Lose the head section
                '@<noscript>.*?</noscript>@siU',            // Lose the noscript section
                '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                '@<svg[^>]*?>.*?</svg>@siU',    // Strip svg tags properly
                '@<![\s\S]*?--[ \t\n\r]*>@',         // Strip multi-line comments including CDATA
                '@[ \t\n\r]+@',         // Strip extra spaces
            ];

            $rawHtml = preg_replace($search, ' ', $content);

            $content = strip_tags($rawHtml);
        }

        $result = array_count_values(str_word_count($content, 1));

        $this->total_unique_words = count($result);

        $this->total_words_found = array_sum($result);

        $this->words_collection = array_keys($result);
    }

    /**
     * @return int
     */
    public function getTotalUniqueWords(): int
    {
        return $this->total_unique_words;
    }

    /**
     * @return float|int
     */
    public function getTotalWordsFound(): float|int
    {
        return $this->total_words_found;
    }

    /**
     * @return int[]|string[]
     */
    public function getWordsCollection(): array
    {
        return $this->words_collection;
    }
}