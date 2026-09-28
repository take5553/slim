<?php
declare(strict_types=1);

use App\Utils;
use App\Todos;

return [
    Utils\View::class => fn () => new Utils\View(
        dirname(__DIR__) . '/templates',
        dirname(__DIR__) . '/var/cache/latte',
        getenv('APP_DEBUG') === '1',
    ),

    Todos\TodoRepository::class => fn () => new Todos\TodoRepository(
        dirname(__DIR__) . '/var/data/todo.sqlite'
    ),

];