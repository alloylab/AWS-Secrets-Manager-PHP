<?php

declare(strict_types=1);

namespace AWSM\File\Json\Exceptions;

use Exception;

class JsonErrorException extends Exception
{
    public function __construct()
    {
        $message = 'JSON error: ' . json_last_error_msg();

        parent::__construct($message);
    }
}
