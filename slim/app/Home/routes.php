<?php
declare(strict_types=1);

use App\Home\HomeController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app): void {
    $app->group('/home', function (RouteCollectorProxy $group) {
        $group->get('', [HomeController::class, 'index']);
    });
};
