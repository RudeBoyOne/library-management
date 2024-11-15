<?php
namespace App\Library\Infrastructure\Persistence\Database;

use PDO;

/**
 * Class Database
 * 
 * Provides utility functions for initializing the database with test data. 
 */
class Database
{

    /**
     * Initializes the database with predefined test data.
     * 
     * @param PDO $connection The PDO connection to the database.
     * @return void 
     */
    public static function initializer(PDO $connection)
    {
        $connection->exec(" INSERT INTO role (name) VALUES ('Professor'), ('Student'); ");
        $connection->exec(" INSERT INTO section (description, localization) VALUES ('Science Fiction', 'A1'), ('Fantasy', 'B2'), ('History', 'C3'); ");
        $connection->exec(" INSERT INTO user (name, email, registration, role) VALUES ('John Doe', 'john.doe@example.com', '12345', 1), ('Jane Doe', 'jane.doe@example.com', '54321', 2); ");
        $connection->exec(" INSERT INTO book (title, section, isbn, author, amount_of_books) VALUES ('Book Title 1', 1, '123-4567890123', 'Author 1', 5), ('Book Title 2', 2, '987-6543210987', 'Author 2', 2); ");
    }
}
