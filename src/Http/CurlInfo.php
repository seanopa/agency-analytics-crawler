<?php

namespace App\Http;

/**
 * Class CurlInfo
 * @package App\Http
 */
class CurlInfo
{
    private $url;
    private $content_type;
    private $http_code;
    private $header_size;
    private $request_size;
    private $filetime;
    private $ssl_verify_result;
    private $redirect_count;
    private $total_time;
    private $namelookup_time;
    private $connect_time;
    private $pretransfer_time;
    private $size_upload;
    private $size_download;
    private $speed_download;
    private $speed_upload;
    private $download_content_length;
    private $upload_content_length;
    private $starttransfer_time;
    private $redirect_time;
    private $redirect_url;
    private $primary_ip;
    private $certinfo;
    private $primary_port;
    private $local_ip;
    private $local_port;
    private $http_version;
    private $protocol;
    private $scheme;
    private $appconnect_time_us;
    private $connect_time_us;
    private $namelookup_time_us;
    private $pretransfer_time_us;
    private $redirect_time_us;
    private $starttransfer_time_us;
    private $total_time_us;

    /**
     * @param array $curl_info
     */
    public function __construct(array $curl_info)
    {
        $reflection = new \ReflectionClass(__CLASS__);

        foreach ($curl_info as $key => $val) {
            if ($reflection->hasProperty($key)) {
               $this->{$key} = $val;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * @return mixed
     */
    public function getHttpCode()
    {
        return $this->http_code;
    }

    /**
     * @return mixed
     */
    public function getHeaderSize()
    {
        return $this->header_size;
    }

    /**
     * @return mixed
     */
    public function getRequestSize()
    {
        return $this->request_size;
    }

    /**
     * @return mixed
     */
    public function getFiletime()
    {
        return $this->filetime;
    }

    /**
     * @return mixed
     */
    public function getSslVerifyResult()
    {
        return $this->ssl_verify_result;
    }

    /**
     * @return mixed
     */
    public function getRedirectCount()
    {
        return $this->redirect_count;
    }

    /**
     * @return mixed
     */
    public function getTotalTime()
    {
        return $this->total_time;
    }

    /**
     * @return mixed
     */
    public function getNamelookupTime()
    {
        return $this->namelookup_time;
    }

    /**
     * @return mixed
     */
    public function getConnectTime()
    {
        return $this->connect_time;
    }

    /**
     * @return mixed
     */
    public function getPretransferTime()
    {
        return $this->pretransfer_time;
    }

    /**
     * @return mixed
     */
    public function getSizeUpload()
    {
        return $this->size_upload;
    }

    /**
     * @return mixed
     */
    public function getSizeDownload()
    {
        return $this->size_download;
    }

    /**
     * @return mixed
     */
    public function getSpeedDownload()
    {
        return $this->speed_download;
    }

    /**
     * @return mixed
     */
    public function getSpeedUpload()
    {
        return $this->speed_upload;
    }

    /**
     * @return mixed
     */
    public function getDownloadContentLength()
    {
        return $this->download_content_length;
    }

    /**
     * @return mixed
     */
    public function getUploadContentLength()
    {
        return $this->upload_content_length;
    }

    /**
     * @return mixed
     */
    public function getStarttransferTime()
    {
        return $this->starttransfer_time;
    }

    /**
     * @return mixed
     */
    public function getRedirectTime()
    {
        return $this->redirect_time;
    }

    /**
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->redirect_url;
    }

    /**
     * @return mixed
     */
    public function getPrimaryIp()
    {
        return $this->primary_ip;
    }

    /**
     * @return mixed
     */
    public function getCertinfo()
    {
        return $this->certinfo;
    }

    /**
     * @return mixed
     */
    public function getPrimaryPort()
    {
        return $this->primary_port;
    }

    /**
     * @return mixed
     */
    public function getLocalIp()
    {
        return $this->local_ip;
    }

    /**
     * @return mixed
     */
    public function getLocalPort()
    {
        return $this->local_port;
    }

    /**
     * @return mixed
     */
    public function getHttpVersion()
    {
        return $this->http_version;
    }

    /**
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @return mixed
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @return mixed
     */
    public function getAppconnectTimeUs()
    {
        return $this->appconnect_time_us;
    }

    /**
     * @return mixed
     */
    public function getConnectTimeUs()
    {
        return $this->connect_time_us;
    }

    /**
     * @return mixed
     */
    public function getNamelookupTimeUs()
    {
        return $this->namelookup_time_us;
    }

    /**
     * @return mixed
     */
    public function getPretransferTimeUs()
    {
        return $this->pretransfer_time_us;
    }

    /**
     * @return mixed
     */
    public function getRedirectTimeUs()
    {
        return $this->redirect_time_us;
    }

    /**
     * @return mixed
     */
    public function getStarttransferTimeUs()
    {
        return $this->starttransfer_time_us;
    }

    /**
     * @return mixed
     */
    public function getTotalTimeUs()
    {
        return $this->total_time_us;
    }
}