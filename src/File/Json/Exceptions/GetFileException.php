<?php

declare(strict_types=1);

namespace AWSM\File\Json\Exceptions;

use Exception;

class GetFileException extends Exception
{
    public function __construct(string $filepath)
    {
        $lastError =  error_get_last()['message'] ?? '';

        $message  = "Error reading file: '$filepath'.";
        $message .= $lastError ? " $lastError." : '';

        parent::__construct($message);
    }
}
