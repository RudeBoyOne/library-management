<?php
namespace Tests\Domain\Repositorys;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\ISBN;
use App\Library\Domain\Entities\Section;
use App\Library\Domain\Repositorys\BookRepository;
use App\Library\Infrastructure\Persistence\Connection;
use PDO;
use PHPUnit\Framework\TestCase;

class BookRepositoryTest extends TestCase
{
    private PDO $connection;
    private BookRepository $bookRepository;
    private string $table = "book";

    public function setUp(): void
    {
        $this->connection = new PDO('sqlite::memory:');
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->exec("
            CREATE TABLE book (
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                title VARCHAR(100) NOT NULL,
                author VARCHAR(100) NOT NULL,
                isbn VARCHAR(13) NOT NULL,
                amount_of_books INTEGER NOT NULL,
                section INTEGER NOT NULL
            )
        ");

        Connection::setInstance($this->connection);

        $this->bookRepository = new BookRepository();
    }

    /**
     * Test Create Book
     *
     * Test performed without Mock, as the ISBN class is final
     * @return void
     */
    public function testCreateBook(): void
    {
        $isbn = new ISBN();
        $isbn->setValue('9781234567890');

        $section = $this->createMock(Section::class);
        $section->method('getId')->willReturn(2);

        $book = new Book();
        $book->setTitle('Test Book')
            ->setAuthor('John Doe')
            ->setIsbn($isbn)
            ->setAmountOfBooks(5
            )->setSection($section);

        $this->assertTrue($this->bookRepository->createBook($book));
        $stmt = $this->connection->query("SELECT * FROM $this->table WHERE isbn = '9781234567890'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('Test Book', $result['title']);
        $this->assertEquals('John Doe', $result['author']);
        $this->assertEquals('9781234567890', $result['isbn']);
        $this->assertEquals(5, $result['amount_of_books']);
        $this->assertEquals(2, $result['section']);
    }

    public function testUpdateBook(): void
    {
        $this->connection->exec(" INSERT INTO book (title, author, isbn, amount_of_books, section) VALUES ('Original Title', 'Original Author', '9781234567890', 10, 1) ");

        $stmt = $this->connection->query("SELECT id FROM book WHERE isbn = '9781234567890'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $bookId = $result['id'];

        $isbn = new ISBN();
        $isbn->setValue('9780987654321');

        $section = $this->createMock(Section::class);
        $section->method('getId')->willReturn(2);

        $book = new Book();
        $book->setId($bookId)
            ->setTitle('Updated Title')
            ->setAuthor('Updated Author')
            ->setIsbn($isbn)
            ->setAmountOfBooks(20)
            ->setSection($section);

        $this->assertTrue($this->bookRepository->updateBook($book));

        $stmt = $this->connection->query("SELECT * FROM book WHERE id = $bookId");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('Updated Title', $result['title']);
        $this->assertEquals('Updated Author', $result['author']);
        $this->assertEquals('9780987654321', $result['isbn']);
        $this->assertEquals(20, $result['amount_of_books']);
        $this->assertEquals(2, $result['section']);
    }

    public function testGetBookById(): void
    {
        $this->connection->exec(" INSERT INTO book (title, author, isbn, amount_of_books, section) VALUES ('Test Book', 'John Doe', '9781234567890', 10, 1) ");

        $stmt = $this->connection->query("SELECT id FROM book WHERE isbn = '9781234567890'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $bookId = $result['id'];

        $book = $this->bookRepository->getBookById($bookId);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals('Test Book', $book->getTitle());
        $this->assertEquals('John Doe', $book->getAuthor());
        $this->assertEquals('9781234567890', $book->getIsbn()->getValue());
        $this->assertEquals(10, $book->getAmountOfBooks());
        $this->assertEquals(1, $book->getSection()->getId());
    }

    public function testGetAllBooks(): void
    {
        $this->connection->exec(" INSERT INTO book (title, author, isbn, amount_of_books, section) VALUES ('Book One', 'Author One', '9781234567890', 10, 1), ('Book Two', 'Author Two', '9780987654321', 5, 2) ");

        $books = $this->bookRepository->getAllBooks();

        $this->assertCount(2, $books);
        $this->assertEquals('Book One', $books[0]['title']);
        $this->assertEquals('Author One', $books[0]['author']);
        $this->assertEquals('9781234567890', $books[0]['isbn']);
        $this->assertEquals(10, $books[0]['amount_of_books']);
        $this->assertEquals(1, $books[0]['section']);

        $this->assertEquals('Book Two', $books[1]['title']);
        $this->assertEquals('Author Two', $books[1]['author']);
        $this->assertEquals('9780987654321', $books[1]['isbn']);
        $this->assertEquals(5, $books[1]['amount_of_books']);
        $this->assertEquals(2, $books[1]['section']);
    }

    public function testDeleteBook(): void
    {
        $this->connection->exec(" INSERT INTO book (title, author, isbn, amount_of_books, section) VALUES ('Test Book', 'John Doe', '9781234567890', 10, 1) ");

        $stmt = $this->connection->query("SELECT id FROM book WHERE isbn = '9781234567890'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $bookId = $result['id'];

        $this->assertTrue($this->bookRepository->deleteBook($bookId));

        $stmt = $this->connection->query("SELECT * FROM book WHERE id = $bookId");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertFalse($result);
    }
}
