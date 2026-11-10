<?php
declare(strict_types=1);

namespace App\Anki;

use App\Anki\Deck\DeckController;
use App\Anki\Study\StudyController;
use App\Auth\AuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app): void {
    $app->group('/anki', function (RouteCollectorProxy $group) {
        $group->get('', [DeckController::class, 'index']);
        $group->get('/decks/demo', [DeckController::class, 'show']);
        $group->get('/decks/demo/study', [StudyController::class, 'show']);
    })->add(AuthMiddleware::class);
};