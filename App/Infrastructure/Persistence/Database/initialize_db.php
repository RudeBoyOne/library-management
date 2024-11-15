<?php

require '../../../../vendor/autoload.php';

use App\Library\Infrastructure\Persistence\Connection;
use App\Library\Infrastructure\Persistence\Database\Database;


/**
 * Initializes the database with predefined test data. 
 */
try {
    Database::initializer(Connection::getInstance());
    echo "Database initialized successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
