<?php

namespace App\Exception;

class UrlNotFoundException extends \Exception
{
    public function __construct(string $message = 'URL not found', int $code = 404, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}