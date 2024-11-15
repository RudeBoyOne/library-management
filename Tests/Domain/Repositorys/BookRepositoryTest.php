<?php
namespace Tests\Domain\Repositorys;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\ISBN;
use App\Library\Domain\Entities\Section;
use App\Library\Domain\Repositorys\BookRepository;
use App\Library\Domain\Repositorys\Implementation\BookRepositoryImpl;
use App\Library\Domain\Repositorys\Implementation\SectionRepositoryImpl;
use App\Library\Infrastructure\Persistence\Connection;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Metadata\Covers;
use Tests\SetupTests;

#[CoversClass(BookRepositoryImpl::class)]
#[CoversClass(Book::class)]
#[CoversClass(ISBN::class)]
#[CoversClass(Section::class)]
#[CoversClass(Connection::class)]
#[CoversClass(SectionRepositoryImpl::class)]
class BookRepositoryTest extends SetupTests
{
    private BookRepository $bookRepository;
    private string $table = "book";

    #[Covers('BookRepositoryImpl::__construct')]
    public function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = new BookRepositoryImpl();

        $this->insertSampleData();
    }

    #[Covers('BookRepositoryImpl::createBook')]
    #[Covers('ISBN::setValue')]
    #[Covers('Section::getId')]
    #[Covers('Book::setTitle')]
    #[Covers('Book::setAuthor')]
    #[Covers('Book::setIsbn')]
    #[Covers('Book::setAmountOfBooks')]
    #[Covers('Book::setSection')]
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

    #[Covers('BookRepositoryImpl::updateBook')]
    #[Covers('ISBN::setValue')]
    #[Covers('Section::getId')]
    #[Covers('Book::setId')]
    #[Covers('Book::setTitle')]
    #[Covers('Book::setAuthor')]
    #[Covers('Book::setIsbn')]
    #[Covers('Book::setAmountOfBooks')]
    #[Covers('Book::setSection')]
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

    #[Covers('BookRepositoryImpl::getBookById')]
    #[Covers('Book::getTitle')]
    #[Covers('Book::getAuthor')]
    #[Covers('Book::getIsbn')]
    #[Covers('Book::getAmountOfBooks')]
    #[Covers('Book::getSection')]
    #[Covers('ISBN::getValue')]
    #[Covers('Section::getId')]
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

    #[Covers('BookRepositoryImpl::getAllBooks')]
    #[Covers('Book::getTitle')]
    #[Covers('Book::getAuthor')]
    #[Covers('Book::getIsbn')]
    #[Covers('Book::getAmountOfBooks')]
    #[Covers('Book::getSection')]
    #[Covers('ISBN::getValue')]
    #[Covers('Section::getId')]
    public function testGetAllBooks(): void
    {
        $this->connection->exec(" INSERT INTO book (title, author, isbn, amount_of_books, section) VALUES ('Book One', 'Author One', '9781234567890', 10, 1), ('Book Two', 'Author Two', '9780987654321', 5, 2) ");

        $books = $this->bookRepository->getAllBooks();

        $this->assertCount(4, $books);
        $this->assertEquals('Book One', $books[2]->getTitle());
        $this->assertEquals('Author One', $books[2]->getAuthor());
        $this->assertEquals('9781234567890', $books[2]->getIsbn()->getValue());
        $this->assertEquals(10, $books[2]->getAmountOfBooks());
        $this->assertEquals(1, $books[2]->getSection()->getId());

        $this->assertEquals('Book Two', $books[3]->getTitle());
        $this->assertEquals('Author Two', $books[3]->getAuthor());
        $this->assertEquals('9780987654321', $books[3]->getIsbn()->getValue());
        $this->assertEquals(5, $books[3]->getAmountOfBooks());
        $this->assertEquals(2, $books[3]->getSection()->getId());
    }

    #[Covers('BookRepositoryImpl::deleteBook')]
    #[Covers('Book::getId')]
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
