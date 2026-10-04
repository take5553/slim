<?php
declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Auth\LoginController;
use App\Auth\OAuthController;

return function (App $app): void {
    $app->group('/auth', function (RouteCollectorProxy $group) {
        // LoginController
        $group->get('', [LoginController::class, 'index']);
        $group->post('/logout', [LoginController::class, 'logout']);

        // OAuthController
        $group->get('/{provider:[a-z]+}', [OAuthController::class, 'redirect']);
        $group->get('/{provider:[a-z]+}/callback', [OAuthController::class, 'callback']);
    });
};