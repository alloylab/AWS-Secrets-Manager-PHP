<?php

declare(strict_types=1);

namespace AWSM\File\Json\Exceptions;

use Exception;

class CreateFileException extends Exception
{
    public function __construct(string $filepath)
    {
        $lastError =  error_get_last()['message'] ?? '';

        $message  = "Could not create file '$filepath'.";
        $message .= $lastError ? " $lastError." : '';

        parent::__construct($message);
    }
}
