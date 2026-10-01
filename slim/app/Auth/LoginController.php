<?php
declare(strict_types=1);

namespace App\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utilities\View;

final class LoginController
{
    private const TEMPLATE = 'auth/index.latte';

    public function __construct(
        private readonly View $view,
    ) {}

    public function index(Request $req, Response $res): Response
    {
        return $this->view->page($req, $res, self::TEMPLATE);
    }
}