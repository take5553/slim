<?php
declare(strict_types=1);

use App\Home\HomeController;
use Slim\App;

return function (App $app): void {
    $app->get('/home', [HomeController::class, 'index']);
};
