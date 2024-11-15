<?php
namespace App\Library\Application\Utils;

/**
 * Class Response
 *
 * Utility class for handling JSON HTTP responses.
 */
class Response
{
    /**
     * Sends a JSON response.
     *
     * @param mixed $data The data to be encoded as JSON.
     * @param int $status The HTTP status code (default is 200).
     * @param array $headers Additional headers to be sent with the response.
     * @param int $options Options for json_encode (default is 0).
     * @return void
     */
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

    /**
     * Sends a JSON response indicating a resource was not found.
     *
     * @param string $message The error message (default is 'Resource not found').
     * @return void
     */
    public static function jsonNotFound($message = 'Resource not found'): void
    {
        self::json(['error' => $message], 404, [], JSON_PRETTY_PRINT);
    }

    /**
     * Sends a JSON response indicating an error occurred.
     *
     * @param string $message The error message (default is 'An error occurred').
     * @param int $status The HTTP status code (default is 500).
     * @return void
     */
    public static function jsonError($message = 'An error occurred', int $status = 500): void
    {
        self::json(['error' => $message], $status, [], JSON_PRETTY_PRINT);
    }

    /**
     * Sends a JSON response indicating a successful operation.
     *
     * @param mixed $data The data to be included in the response.
     * @param int $status The HTTP status code.
     * @param string $message The success message (default is 'Operation successful').
     * @return void
     */
    public static function jsonSuccess($data = [], $status, $message = 'Operation successful'): void
    {
        self::json(['message' => $message, 'data' => $data], $status, [], JSON_PRETTY_PRINT);
    }

    /**
     * Sends a 204 No Content response.
     *
     * @return void
     */
    public static function noContent(): void
    {
        http_response_code(204);
        exit;
    }
}
