<?php

namespace Napps\Rest\Middleware;

interface IMiddleware
{
    public function setData($data);
    public function run();
}
