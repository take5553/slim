<?php
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

$root = dirname(__DIR__);
$debug = getenv('APP_DEBUG') === '1';


$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/../config/container.php')
    ->build();
AppFactory::setContainer($container);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware($debug, true, true);

(require __DIR__ . '/../config/routes.php')($app);

$app->run();
