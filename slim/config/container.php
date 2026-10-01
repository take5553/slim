<?php
declare(strict_types=1);

use App\Utilities;
use App\Todo;

return [
    PDO::class => fn () => new PDO(
        sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $_ENV['DB_HOST'],
            $_ENV['DB_PORT'],
            $_ENV['DB_DATABASE']
        ),
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    ),

    Utilities\View::class => fn () => new Utilities\View(
        dirname(__DIR__) . '/templates',
        dirname(__DIR__) . '/var/cache/latte',
        $_ENV['APP_DEBUG'] === '1',
    ),

    Todo\TodoRepository::class => fn (PDO $pdo) => new Todo\TodoRepository($pdo),

];