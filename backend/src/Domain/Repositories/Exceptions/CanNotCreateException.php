<?php

namespace Backend\Domain\Repositories\Exceptions;


use Backend\Application\Exceptions\Exception;
use Throwable;

class CanNotCreateException extends Exception
{

    public function __construct(int $code = 0, Throwable $previous = null)
    {
        parent::__construct('Can not create', $code, $previous);
    }
}