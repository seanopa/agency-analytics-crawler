<?php

namespace App\Http;
/**
 * Class Response
 * @package App\Http
 */
class Response
{
    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return void
     */
    public static function flush(\Symfony\Component\HttpFoundation\Response $response)
    {
        ob_end_clean();
            header("Connection: close");
            ignore_user_abort(true);

        ob_start();
            echo $response->getContent();
            $size = ob_get_length();
            header("Content-Length: $size");
        ob_end_flush();

        flush();
    }
}