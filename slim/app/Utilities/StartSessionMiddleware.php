<?php
declare(strict_types=1);

namespace App\Utilities;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class StartSessionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Session $session,
    ) {}

    public function process(Request $req, RequestHandlerInterface $handler): Response
    {
        $this->session->start();
        $res = $handler->handle($req);
        session_write_close();
        return $res;
    }
}
