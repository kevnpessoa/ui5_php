<?php

namespace Napps\Rest\Middleware;

class Middleware implements IMiddleware
{
    private $data;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function run()
    {
        //
    }
}
