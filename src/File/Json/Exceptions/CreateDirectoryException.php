<?php

declare(strict_types=1);

namespace AWSM\File\Json\Exceptions;

use Exception;

class CreateDirectoryException extends Exception
{
    public function __construct(string $path)
    {
        $lastError =  error_get_last()['message'] ?? '';

        $message  = "Could not create directory in '$path'.";
        $message .= $lastError ? " $lastError." : '';

        parent::__construct($message);
    }
}
