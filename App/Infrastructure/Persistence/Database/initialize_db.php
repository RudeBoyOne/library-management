<?php

require '../../../../vendor/autoload.php';

use App\Library\Infrastructure\Persistence\Connection;
use App\Library\Infrastructure\Persistence\Database\Database;

try {
    Database::initializer(Connection::getInstance());
    echo "Database initialized successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
