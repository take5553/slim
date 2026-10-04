<?php
declare(strict_types=1);

use App\Utilities;
use App\Todo;
use App\Auth;
use App\Utilities\Session;
use League\OAuth2\Client\Provider\Github;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;

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

    ResponseFactoryInterface::class => fn () => new ResponseFactory(),

    Todo\TodoRepository::class => fn (PDO $pdo) => new Todo\TodoRepository($pdo),

    Auth\OAuthController::class => fn (Session $session) => new Auth\OAuthController(
        [
            'github' => new Github([
                'clientId' => $_ENV['GITHUB_CLIENT_ID'],
                'clientSecret' => $_ENV['GITHUB_CLIENT_SECRET'],
                'redirectUri' => $_ENV['APP_URL'] . '/auth/github/callback'
            ]),
        ],
        $session,
    ),
];
