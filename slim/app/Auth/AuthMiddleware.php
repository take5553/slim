<?php
declare(strict_types=1);

namespace App\Auth;

use App\Utilities\Session;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Session $session,
        private readonly ResponseFactoryInterface $responseFactory,
    ) {}

    public function process(Request $req, RequestHandlerInterface $handler): Response
    {
        $userId = $this->session->get('user_id');

        if (is_null($userId)) {
            $res = $this->responseFactory->createResponse();
            if ($req->getHeaderLine('Hx-Request') === 'true') {
                return $res->withHeader('Hx-Redirect', '/auth');
            }
            return $res->withStatus(302)->withHeader('Location', '/auth');
        }

        return $handler->handle($req->withAttribute('user_id', (int) $userId));
    }
}
