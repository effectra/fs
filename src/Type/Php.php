<?php

declare(strict_types=1);

namespace Effectra\Fs\Type;

class Php
{
    public static function toArray(array|object $configData): string
    {
        $decode = JSON::encode($configData, JSON_PRETTY_PRINT);

        return  str_replace(['{', '}', ':', '"'], ['[', ']', '=>', "'"], $decode);
    }


}