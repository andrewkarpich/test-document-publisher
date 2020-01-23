<?php

namespace Backend\Application\Requests;


class Request extends \Phalcon\Http\Request
{
    protected $rawBodyChanged;

    public function getRawBody(): string
    {
        return $this->rawBodyChanged;
    }

    public function setRawBody(string $content): void
    {
        $this->rawBodyChanged = $content;
    }

}