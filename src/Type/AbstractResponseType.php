<?php

namespace App\Type;

class AbstractResponseType implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}