<?php

declare(strict_types=1);

function env_value(string $key, ?string $default = null): ?string
{
    static $env = null;

    if ($env === null) {
        $env = [];
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';

        if (is_file($path)) {
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                $line = trim($line);

                if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                    continue;
                }

                [$name, $value] = explode('=', $line, 2);
                $value = trim($value);
                $value = trim($value, "\"'");
                $env[trim($name)] = $value;
            }
        }
    }

    return $env[$key] ?? getenv($key) ?: $default;
}

function app_config(): array
{
    return [
        'app_name' => env_value('APP_NAME', 'Soto Pak Tio'),
        'app_url' => env_value('APP_URL', 'http://localhost:8000'),
        'db' => [
            'host' => env_value('DB_HOST', '127.0.0.1'),
            'port' => env_value('DB_PORT', '3306'),
            'database' => env_value('DB_DATABASE', 'soto_pak_tio'),
            'username' => env_value('DB_USERNAME', 'root'),
            'password' => env_value('DB_PASSWORD', ''),
        ],
        'admin' => [
            'username' => env_value('ADMIN_USERNAME', 'admin'),
            'password' => env_value('ADMIN_PASSWORD', 'ubah-password-ini'),
        ],
    ];
}
