<?php

define("BASEPATH", dirname(__DIR__));

require_once BASEPATH . '/vendor/autoload.php';

$app = \App\App::getInstance();

$app->run();