<?php
namespace App\Home;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utilities\View;

final class HomeController
{
    private const TEMPLATE = 'home/index.latte';

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