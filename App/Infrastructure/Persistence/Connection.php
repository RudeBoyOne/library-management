<?php
namespace App\Library\Infrastructure\Persistence;

use PDO;

class Connection
{
    private static $instance = null;
    private $path;
    private PDO $connection;

    public function __construct()
    {
        $this->path = getenv('DB_PATH');
        $dsn = "sqlite:{$this->path}";
        $this->connection = new PDO($dsn, null, null, [PDO::ATTR_PERSISTENT => true]);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}
