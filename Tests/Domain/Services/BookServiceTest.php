<?php
namespace Tests\Domain\Services;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\ISBN;
use App\Library\Domain\Entities\Section;
use App\Library\Domain\Repositorys\BookRepository;
use App\Library\Domain\Repositorys\Implementation\SectionRepositoryImpl;
use App\Library\Domain\Services\BookService;
use App\Library\Domain\Services\SectionService;
use App\Library\Infrastructure\Persistence\Connection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\Covers;

#[CoversClass(BookService::class)]
#[CoversClass(Book::class)]
#[CoversClass(Section::class)]
#[CoversClass(ISBN::class)]
#[CoversClass(SectionService::class)]
#[CoversClass(SectionRepositoryImpl::class)]
#[CoversClass(Connection::class)]
class BookServiceTest extends TestCase
{
    private $bookRepositoryMock;
    private $bookService;

    protected function setUp(): void
    {
        $this->bookRepositoryMock = $this->createMock(BookRepository::class);
        $this->bookService = new BookService($this->bookRepositoryMock);
    }

    #[Covers('BookService::create')]
    public function testCreate()
    {
        $data = (object) ['title' => 'Test Book', 'author' => 'John Doe', 'isbn' => '9781234567890', 'amountOfBooks' => 10, 'section' => 1];
        $this->bookRepositoryMock->method('createBook')->willReturn(true);
        $result = $this->bookService->create($data);
        $this->assertTrue($result);
    }

    #[Covers('BookService::update')]
    public function testUpdate()
    {
        $data = (object) ['title' => 'Updated Book', 'author' => 'Jane Doe', 'isbn' => '9780987654321', 'amountOfBooks' => 5, 'section' => 2];
        $this->bookRepositoryMock->method('updateBook')->willReturn(true);
        $result = $this->bookService->update(1, $data);
        $this->assertTrue($result);
    }

    #[Covers('BookService::getAll')]
    public function testGetAll()
    {
        $books = [$this->createMock(Book::class), $this->createMock(Book::class)];
        $this->bookRepositoryMock->method('getAllBooks')->willReturn($books);
        $result = $this->bookService->getAll();
        $this->assertSame($books, $result);
    }

    #[Covers('BookService::getBookById')]
    public function testGetBookById()
    {
        $book = $this->createMock(Book::class);
        $this->bookRepositoryMock->method('getBookById')->willReturn($book);
        $result = $this->bookService->getBookById(1);
        $this->assertSame($book, $result);
    }

    #[Covers('BookService::delete')]
    public function testDelete()
    {
        $this->bookRepositoryMock->method('deleteBook')->willReturn(true);
        $result = $this->bookService->delete(1);
        $this->assertTrue($result);
    }
}
