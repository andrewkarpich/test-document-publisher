<?php

namespace Backend\Domain\Services\Exceptions;

use Backend\Application\Exceptions\Exception;
use Throwable;

class AlreadyPublishedException extends Exception
{

    public function __construct(int $code = 0, Throwable $previous = null)
    {
        parent::__construct('Already published', $code, $previous);
    }

}