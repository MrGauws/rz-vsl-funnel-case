<?php

namespace App\Support;

class Response
{
    public static function redirect(string $path): void
    {
        header('Location: ' . $path, true, 302);
        exit;
    }

    public static function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

