<?php

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$container = new \DI\ContainerBuilder();
$container->addDefinitions(require_once __DIR__.'/../config/container.php');

return $container->build();



