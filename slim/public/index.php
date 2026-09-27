<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Utils\View;

require __DIR__ . '/../vendor/autoload.php';

$root = dirname(__DIR__);
$debug = getenv('APP_DEBUG') === '1';

$view = new View($root . '/templates', $root . '/var/cache/latte', $debug);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware($debug, true, true);

$app->get('/', function (Request $req, Response $res, $args) use ($view)  {
    $params = [
        'message' => "hello world! " . date('Y-m-d H:i:s') . implode("", $args)
    ];

    return $view->page($req, $res, '/pages/index.latte', $params);
});



$app->run();
