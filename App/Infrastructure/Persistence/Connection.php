<?php
namespace App\Library\Infrastructure\Persistence;

use PDO;

/**
 * Singleton class to manage database connection.
 *
 * This class uses the Singleton pattern to ensure that only one instance
 * of the database connection is created and reused during the application's lifecycle.
 * The connection is established using PDO with a database path defined by an environment variable.
 */
class Connection
{
    /**
     * Single instance of the connection.
     * @var self|null
     */
    private static $instance = null;
    /**
     * Database path.
     * @var string
     */
    private $path;
    /**
     * PDO instance for the database connection.
     * @var PDO
     */
    private PDO $connection;

    /**
     * Class constructor.
     *
     * The constructor is private to prevent creating multiple instances of the class.
     * It initializes the database connection using the path specified in the DB_PATH environment variable.
     * @throws \PDOException If the database connection fails.
     */
    public function __construct()
    {
        $this->path = getenv('DB_PATH');
        $dsn = "sqlite:{$this->path}";
        $this->connection = new PDO($dsn, null, null, [PDO::ATTR_PERSISTENT => true]);
    }

    /**
     * Gets the single instance of the connection.
     * @return PDO The PDO instance for the database connection.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }

    /**
     * Sets the instance as an in-memory connection for testing purposes.
     * @param PDO $connection The PDO instance to use.
     */
    public static function setInstance(PDO $connection): void
    {
        self::$instance = new self();
        self::$instance->connection = $connection;
    }
}
