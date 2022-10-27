<?php

declare(strict_types=1);

namespace AWSM\File\Json\Exceptions;

use Exception;

class UnavailableMethodException extends Exception
{
    public function __construct(string $method)
    {
        $message = 'The "' . $method . '" method is not available for remote JSON files.';

        parent::__construct($message);
    }
}
