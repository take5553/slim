<?php

namespace App\Top;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utilities\View;

final class TopController
{
    private const TEMPLATE = 'top/index.latte';

    public function __construct(
        private readonly View $view,
    ) {}

    public function index(Request $req, Response $res): Response
    {
        return $this->view->page($req, $res, self::TEMPLATE, [
            'message' => 'TOP PAGE ' . date('Y-m-d H:i:s'),
        ]);
    }
}
