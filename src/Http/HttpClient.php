<?php
namespace App\Http;

/**
 * Class HttpClient
 * @package App\Http
 */
class HttpClient
{
    const HTTP_REQUEST_SUCCESSFUL = 1;

    const HTTP_REQUEST_FAILED = 2;

    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';

    private string $url;
    private string $method;
    private ?array $params;

    /**
     * @param string $url
     * @param string $method
     * @param array|null $params
     */
    public function __construct(string $url, string $method = self::HTTP_METHOD_GET, ?array $params = [])
    {
        $this->url = $url;
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * @return CurlDocument
     * @throws HttpException
     */
    public function load(): CurlDocument
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_ENV['HTTP_USER_AGENT']);

        $contents = curl_exec($ch);

        curl_close($ch);

        $curl_info  = curl_getinfo($ch);

        $http_code = $curl_info['http_code'];

        if ($http_code < 200 || $http_code >= 400) {
            throw new HttpException(sprintf('Request returned an error code of %s', $http_code), $http_code);
        }

        return new CurlDocument(new CurlInfo($curl_info), $contents);
    }
}