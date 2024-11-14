<?php
namespace App\Library\Application\Utils;

class Response
{
    public static function json($data, int $status = 200, array $headers = [], int $options = 0): void
    {
        http_response_code($status);

        foreach ($headers as $header => $value) {
            header("{$header}: {$value}");
        }

        header('Content-Type: application/json');
        echo json_encode($data, $options);
        exit;
    }
    public static function jsonNotFound($message = 'Resource not found'): void
    {
        self::json(['error' => $message], 404, [], JSON_PRETTY_PRINT);
    }
    public static function jsonError($message = 'An error occurred', int $status = 500): void
    {
        self::json(['error' => $message], $status, [], JSON_PRETTY_PRINT);
    }

    public static function jsonSuccess($data = [], $status, $message = 'Operation successful'): void
    {
        self::json(['message' => $message, 'data' => $data], $status, [], JSON_PRETTY_PRINT);
    }

    public static function noContent(): void
    {
        http_response_code(204);
        exit;
    }
}
