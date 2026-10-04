<?php

use App\Utilities\StartSessionMiddleware;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$debug = $_ENV['APP_DEBUG'] === '1';

$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/../config/container.php')
    ->build();
AppFactory::setContainer($container);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(StartSessionMiddleware::class);
$app->addErrorMiddleware($debug, true, true);

(require __DIR__ . '/../config/routes.php')($app);

$app->run();
