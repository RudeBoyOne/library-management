<?php
namespace Tests;

use App\Library\Infrastructure\Persistence\Connection;
use PDO;
use PHPUnit\Framework\TestCase;

class SetupTests extends TestCase
{
    protected $connection;
    protected function setUp(): void
    {
        $this->connection = new PDO('sqlite::memory:');
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        Connection::setInstance($this->connection);
        $this->createTables();
    }

    protected function createTables(): void
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS role (
                id INTEGER PRIMARY KEY,
                name VARCHAR(100) NOT NULL
                );

            CREATE TABLE IF NOT EXISTS section(
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                description VARCHAR NOT NULL,
                localization VARCHAR(20) NOT NULL
            );

            CREATE TABLE IF NOT EXISTS user(
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                registration VARCHAR(100) NOT NULL,
                role INTEGER NOT NULL,
                CONSTRAINT fk_function FOREIGN KEY (role) REFERENCES role(id)
            );

            CREATE TABLE IF NOT EXISTS book(
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                title VARCHAR(100) NOT NULL,
                section INTEGER NOT NULL,
                isbn VARCHAR(15) NOT NULL,
                author VARCHAR(100) NOT NULL,
                amount_of_books INTEGER NOT NULL,
                CONSTRAINT fk_section FOREIGN KEY (section) REFERENCES section(id)
                );

            CREATE TABLE IF NOT EXISTS loan(
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                date_loan DATETIME NOT NULL,
                return_loan DATETIME NOT NULL,
                id_user INTEGER NOT NULL,
                CONSTRAINT fk_user FOREIGN KEY (id_user) REFERENCES user(id)
            );

            CREATE TABLE IF NOT EXISTS loan_books(
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                id_loan INTEGER NOT NULL,
                id_book INTEGER NOT NULL,
                CONSTRAINT fk_loan_books FOREIGN KEY (id_loan) REFERENCES loan(id), CONSTRAINT fk_book FOREIGN KEY (id_book) REFERENCES book(id)
            );
        ");
    }

    protected function insertSampleData(): void
    {
        $this->connection->exec(" INSERT INTO role (name) VALUES ('Professor'); ");
        $this->connection->exec(" INSERT INTO role (name) VALUES ('Student'); ");

        $this->connection->exec(" INSERT INTO user (name, email, registration, role) VALUES ('Professor User', 'professor@example.com', '123456', 1); ");

        $this->connection->exec(" INSERT INTO user (name, email, registration, role) VALUES ('Student User', 'student@example.com', '654321', 2); ");

        $this->connection->exec(" INSERT INTO section (description, localization) VALUES ('Science Fiction', 'A1'), ('Fantasy', 'B2'); ");

        $this->connection->exec(" INSERT INTO book (title, author, isbn, amount_of_books, section) VALUES ('Book Title 1', 'Author 1', '123-4567890123', 3, 1), ('Book Title 2', 'Author 2', '987-6543210987', 5, 2); ");
    }
}
