<?php

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/env.php';

$app = new Napps\Rest\App();
$app->setRenderer(new Napps\Rest\Renderer\PHPRenderer());

require __DIR__ . '/router.php';

$app->run();
