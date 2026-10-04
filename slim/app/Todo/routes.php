<?php
declare(strict_types=1);

use App\Auth\AuthMiddleware;
use App\Todo\TodoController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app): void {
    $app->group('/todo', function (RouteCollectorProxy $group) {
        $group->get('', [TodoController::class, 'index']);
        $group->post('', [TodoController::class, 'store']);
        $group->patch('/{id:[0-9]+}/toggle', [TodoController::class, 'toggle']);
        $group->delete('/{id:[0-9]+}', [TodoController::class, 'destroy']);
    })->add(AuthMiddleware::class);
};
