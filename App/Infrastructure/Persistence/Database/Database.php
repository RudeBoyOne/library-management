<?php
namespace App\Library\Infrastructure\Persistence\Database;

use App\Library\Infrastructure\Persistence\Connection;
use PDO;

class Database
{

    public static function initializer(PDO $connection)
    {
        $connection->exec(" INSERT INTO role (name) VALUES ('Professor'), ('Student'); ");
        $connection->exec(" INSERT INTO section (description, localization) VALUES ('Science Fiction', 'A1'), ('Fantasy', 'B2'), ('History', 'C3'); ");
        $connection->exec(" INSERT INTO user (name, email, registration, role) VALUES ('John Doe', 'john.doe@example.com', '12345', 1), ('Jane Doe', 'jane.doe@example.com', '54321', 2); ");
        $connection->exec(" INSERT INTO book (title, section, isbn, author, amount_of_books) VALUES ('Book Title 1', 1, '123-4567890123', 'Author 1', 5), ('Book Title 2', 2, '987-6543210987', 'Author 2', 2); ");
    }
}
