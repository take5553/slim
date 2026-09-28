<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Utils\View;
use App\Repos\TodoRepository;
use Slim\Exception\HttpNotFoundException;

require __DIR__ . '/../vendor/autoload.php';

$root = dirname(__DIR__);
$debug = getenv('APP_DEBUG') === '1';

$view = new View($root . '/templates', $root . '/var/cache/latte', $debug);
$repo = new TodoRepository($root . '/var/data/todo.sqlite');

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware($debug, true, true);

$itemWithCounter = fn (Response $res, array $todo) => $view->fragments($res, 'todos/index.latte', [
    'item' => ['todo' => $todo],
    'counter' => ['remining' => $repo->remaining(), 'oob' => true],
]);

$app->get('/', function (Request $req, Response $res, $args) use ($view)  {
    $params = [
        'message' => "hello world! " . date('Y-m-d H:i:s') . implode("", $args)
    ];

    return $view->page($req, $res, '/pages/index.latte', $params);
});

$app->get('/todos', function (Request $req, Response $res) use ($repo, $view) {
    return $view->page($req, $res, '/todos/index.latte', [
        'todos' => $repo->all(),
        'remaining' => $repo->remaining(),
    ]);
});

$app->post('/todos', function (Request $req, Response $res) use ($repo, $itemWithCounter) {
    $title = trim((string) ($req->getParsedBody()['title'] ?? ''));
    if ($title === '' || mb_strlen($title) > 100) {
        return $res->withStatus(422);
    }
    return $itemWithCounter($res, $repo->add($title));
});

$app->patch('/todos/{id:[0-9]+}/toggle', function (Request $req, Response $res, array $args) use ($repo, $itemWithCounter) {
    $todo = $repo->toggle((int) $args['id']) ?? throw new HttpNotFoundException($req);
    return $itemWithCounter($res, $todo);
});

$app->delete('/todos/{id:[0-9]+}', function (Request $req, Response $res, array $args) use ($repo, $view) {
    if(!$repo->delete((int) $args['id'])) {
        throw new HttpNotFoundException($req);
    }
    return $view->fragments($res, 'todos/index.latte', [
        'counter' => ['remaining' => $repo->remaining(), 'oob' => true]
    ]);

});

$app->run();
