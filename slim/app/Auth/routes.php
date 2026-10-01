<?php
declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Auth\LoginController;

return function (App $app): void {
    $app->group('/auth', function (RouteCollectorProxy $group) {
        $group->get('', [LoginController::class, 'index']);
    });
};