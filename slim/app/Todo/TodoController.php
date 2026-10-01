<?php

namespace App\Todo;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Utilities\View;
use Slim\Exception\HttpNotFoundException;

final class TodoController
{
    private const TEMPLATE = 'todos/index.latte';

    public function __construct(
        private readonly View $view,
        private readonly TodoRepository $repo,
    ) {}

    public function index(Request $req, Response $res): Response
    {
        return $this->view->page($req, $res, self::TEMPLATE, [
            'todos' => $this->repo->all(),
            'remaining' => $this->repo->remaining(),
        ]);
    }

    public function store(Request $req, Response $res): Response
    {
        $title = trim((string) ($req->getParsedBody()['title'] ?? ''));
        if($title === '' || mb_strlen($title) > 100) {
            return $res->withStatus(422);
        }
        return $this->itemWithCounter($res, $this->repo->add($title));
    }

    public function toggle(Request $req, Response $res, array $args): Response
    {
        $todo = $this->repo->toggle((int) $args['id']) ?? throw new HttpNotFoundException($req);
        return $this->itemWithCounter($res, $todo);
    }

    public function destroy(Request $req, Response $res, array $args): Response
    {
        if (!$this->repo->delete((int) $args['id'])) {
            throw new HttpNotFoundException($req);
        }

        return $this->view->fragments($res, self::TEMPLATE, [
            'counter' => ['remaining' => $this->repo->remaining(), 'oob' => true],
        ]);
    }

    private function itemWithCounter(Response $res, array $todo): Response
    {
        return $this->view->fragments($res, self::TEMPLATE, [
            'item' => ['todo' => $todo],
            'counter' => ['remaining' => $this->repo->remaining(), 'oob' => true]
        ]);
    }

}