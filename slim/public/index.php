<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../utils/latte-render.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) use ($render)  {
    $params = [
        'message' => "hello world! " . date('Y-m-d h:i:s') . implode("", $args)
    ];

    return $render($request, $response, '/pages/index.latte', $params);
});

$app->run();
