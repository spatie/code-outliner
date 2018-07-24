<?php

namespace Spatie\CodeOutline\Exceptions;

use Exception;

class FileNotFound extends Exception
{
    public static function path(string $path): FileNotFound
    {
        return new self("File not found: {$path}");
    }
}
