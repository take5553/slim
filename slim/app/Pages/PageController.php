<?php
namespace App\Pages;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\View;

final class PageController
{
    private const TEMPLATE = 'pages/index.latte';

    public function __construct(
        private readonly View $view,
    ) { }

    public function index(Request $req, Response $res): Response
    {
        return $this->view->page($req, $res, self::TEMPLATE, [
            'message' => "hello world with container! " . date('Y-m-d H:i:s'),
        ]);
    }

}