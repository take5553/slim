<?php
declare(strict_types=1);

namespace App\Auth;

use App\Utilities\Session;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

final class OAuthController
{
    /** @param array<string, AbstractProvider> $providers */
    public function __construct(
        private readonly array $providers,
        private readonly Session $session,
        private readonly UserRepository $users,
    ) { }

    public function redirect(Request $req, Response $res, array $args): Response
    {
        $provider = $this->providers[$args['provider']] ?? throw new HttpNotFoundException($req);

        $url = $provider->getAuthorizationUrl();
        $this->session->set('oauth_state', $provider->getState());

        return $res->withStatus(302)->withHeader('Location', $url);
    }

    public function callback(Request $req, Response $res, array $args): Response
    {
        $provider = $this->providers[$args['provider']] ?? throw new HttpNotFoundException($req);
        $params = $req->getQueryParams();

        $state = (string) $this->session->pull('oauth_state', '');
        if (isset($params['error']) || $state === '' || !hash_equals($state, (string) ($params['state'] ?? ''))) {
            return $res->withStatus(302)->withHeader('Location', '/auth');
        }

        try {
            $token = $provider->getAccessToken('authorization_code', ['code' => $params['code'] ?? '']);
            $owner = $provider->getResourceOwner($token);

        } catch (IdentityProviderException $e) {
            error_log($e->getMessage());
            return $res->withStatus(302)->withHeader('Location', '/auth');
        }

        $data = $owner->toArray();
        // ユーザーをDBから取得するか、新規作成するか
        $userId = $this->users->findOrCreateBySocial(
            $args['provider'],
            (string) $owner->getId(),
            $data['name'] ?? $data['login'] ?? null,
            $data['email'] ?? null,
        );

        $this->session->regenerate();
        $this->session->set('user_id', $userId);

        return $res->withStatus(302)->withHeader('Location', '/home');
    }
}
