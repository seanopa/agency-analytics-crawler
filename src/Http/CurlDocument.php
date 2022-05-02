<?php

namespace App\Http;

/**
 * Class CurlDocument
 * @package App\Http
 */
class CurlDocument extends \DOMDocument
{
    private CurlInfo $info;
    private string $rawHtml;

    /**
     * @param CurlInfo $info
     * @param string|null $content
     * @param string $version
     * @param string $encoding
     */
    public function __construct(CurlInfo $info, ?string $content, string $version = '1.0', string $encoding = '')
    {
        parent::__construct($version, $encoding);

        $this->info = $info;

        $this->setHtmlDocumentContent($content);
    }

    /**
     * @param string $content
     * @return void
     */
    private function setHtmlDocumentContent(string $content)
    {
        while (str_starts_with($content, 'HTTP')) {
            list($header, $content) = explode("\r\n\r\n", $content, 2);
        }

        libxml_use_internal_errors(true);

        $this->rawHtml = $content;
        $this->loadHTML($content);
    }

    /**
     * @return CurlInfo
     */
    public function getCurlInfo(): CurlInfo
    {
        return $this->info;
    }

    /**
     * @return string
     */
    public function getRawHtml(): string
    {
        return $this->rawHtml;
    }
}