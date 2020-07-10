<?php

namespace Napps\Rest\Renderer;

interface PHPRendererInterface
{
    public function setData($data);
    public function run();
}
