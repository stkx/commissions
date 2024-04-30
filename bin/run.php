<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$container = require_once dirname(__DIR__) . '/bootstrap/bootstrap.php';

$app = $container->get(\App\Command\CalculateCommissionCommand::class);

try {
    if (!isset($argv[1])) {
        throw new Exception('No filename provided');
    }

    $result = $app->run($argv[1]);

    foreach ($result as $row) {
        echo $row . PHP_EOL;
    }

} catch (Throwable $exception) {
    echo sprintf("Error: %s" . PHP_EOL, $exception->getMessage());
}


