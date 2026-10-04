<?php
declare(strict_types=1);

namespace App\Utilities;

final class Session
{
    public function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => str_starts_with($_ENV['APP_URL'] ?? '', 'https://'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function pull(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION[$key] ?? $default;
        unset($_SESSION[$key]);
        return $value;
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }
}
