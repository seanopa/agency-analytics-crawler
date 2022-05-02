<?php
namespace App\Message;

interface MessageInterface extends \JsonSerializable
{
    public static function getInstanceFromArray(array $content);
}