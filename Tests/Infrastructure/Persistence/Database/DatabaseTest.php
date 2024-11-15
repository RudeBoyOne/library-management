<?php
namespace Tests\Infrastructure\Persistence\Database;

use App\Library\Infrastructure\Persistence\Database\Database;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\Covers;

#[CoversClass(Database::class)]
class DatabaseTest extends TestCase
{
    private PDO $connection;

    protected function setUp(): void
    {
        $this->connection = new PDO('sqlite::memory:');
        $this->createTables();
    }

    private function createTables(): void
    {
        $this->connection->exec("CREATE TABLE role (id INTEGER PRIMARY KEY, name TEXT)");
        $this->connection->exec("CREATE TABLE section (id INTEGER PRIMARY KEY, description TEXT, localization TEXT)");
        $this->connection->exec("CREATE TABLE user (id INTEGER PRIMARY KEY, name TEXT, email TEXT, registration TEXT, role INTEGER)");
        $this->connection->exec("CREATE TABLE book (id INTEGER PRIMARY KEY, title TEXT, section INTEGER, isbn TEXT, author TEXT, amount_of_books INTEGER)");
    }

    #[Covers('Database::initializer')]
    public function testInitializer()
    {
        Database::initializer($this->connection);
        // Verificar se os dados foram inseridos na tabela role
        $stmt = $this->connection->query("SELECT COUNT(*) FROM role");
        $roleCount = $stmt->fetchColumn();
        $this->assertEquals(2, $roleCount);
        // Verificar se os dados foram inseridos na tabela section
        $stmt = $this->connection->query("SELECT COUNT(*) FROM section");
        $sectionCount = $stmt->fetchColumn();
        $this->assertEquals(3, $sectionCount);
        // Verificar se os dados foram inseridos na tabela user
        $stmt = $this->connection->query("SELECT COUNT(*) FROM user");
        $userCount = $stmt->fetchColumn();
        $this->assertEquals(2, $userCount);
        // Verificar se os dados foram inseridos na tabela book
        $stmt = $this->connection->query("SELECT COUNT(*) FROM book");
        $bookCount = $stmt->fetchColumn();
        $this->assertEquals(2, $bookCount);
    }
}
