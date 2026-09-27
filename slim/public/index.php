<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../utils/latte-render.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) use ($render)  {
    $params = [
        'message' => "hello world! " . date('Y-m-d H:i:s') . implode("", $args)
    ];

    return $render($request, $response, '/pages/index.latte', $params);
});


$app->get('/todos', function(Request $request, Response $response) use ($render) {
    $todos = [
        'hogehoge',
        'hugahuga'
    ];
    return $render( $request, $response, '/todos/index.latte', ['todos' => $todos]);
});

$app->post('/todos', function(Request $request, Response $response) use ($render) {
    $title = trim((string) ($request->getParsedBody()['title'] ?? ''));
    if($title === '' || mb_strlen($title) > 100) {
        return $response->withStatus(422);
    }
    return $render( $request, $response, '/todos/_item.latte', ['todo' => $title]);

});

$app->run();
