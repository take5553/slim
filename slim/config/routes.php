<?php
declare(strict_types=1);

use Slim\App;

return function (App $app): void {
    $modules = [
        'Home',
        'Todo',
    ];

    foreach ($modules as $module) {
        $file = dirname(__DIR__) . "/app/{$module}/routes.php";
        if (!is_file($file)) {
            if (($_ENV['APP_DEBUG'] ?? '0') === '1') {
                error_log("routes.php not found: {$file}");
            }
            continue;
        }
        (require dirname(__DIR__) . "/app/{$module}/routes.php")($app);
    }
};
