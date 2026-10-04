<?php
declare(strict_types=1);

namespace App\Auth;

use App\Utilities\Session;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use League\OAuth2\Client\Provider\AbstractProvider;

final class OAuthController
{
    /** @param array<string, AbstractProvider> $providers */
    public function __construct(
        private readonly array $providers,
        private readonly Session $session,
    ) { }

    public function redirect(Request $req, Response $res, array $args): Response
    {
        $provider = $this->providers[$args['provider']] ?? throw new HttpNotFoundException($req);

        $url = $provider->getAuthorizationUrl();
        $this->session->set('oauth_state', $provider->getState());

        return $res->withStatus(302)->withHeader('Location', $url);
    }
}
