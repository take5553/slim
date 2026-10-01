<?php
declare(strict_types=1);

use App\Top\TopController;
use Slim\App;
use Slim\Psr7\Request;

return function (App $app): void {
    $app->get('/', [TopController::class, 'index']);
};
