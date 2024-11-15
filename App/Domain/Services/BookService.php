<?php
namespace App\Library\Domain\Services;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\ISBN;
use App\Library\Domain\Repositorys\BookRepository;
use App\Library\Domain\Repositorys\Implementation\SectionRepositoryImpl;
use App\Library\Domain\Services\SectionService;

/**
 * Class BookService
 * 
 * Provides services for managing books. 
 */
class BookService
{
    private BookRepository $bookRepository;
    private SectionService $sectionService;

    /**
     * Constructor for the BookService class.
     * 
     * Initializes the book repository and section service.
     * @param BookRepository $bookRepository The repository for managing books.
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;

        $sectionRepository = new SectionRepositoryImpl();
        $this->sectionService = new SectionService($sectionRepository);
    }

    /**
     * Creates a new book.
     * 
     * @param object $data The data for creating the book.
     * @return bool Returns true if the book was successfully created, false otherwise.
     */
    public function create($data): bool
    {
        $book = $this->assemblerBookEntitie($data);
        return $this->bookRepository->createBook($book);
    }

    /**
     * Updates an existing book.
     * 
     * @param int $idBook The ID of the book to be updated.
     * @param object $data The data for updating the book.
     * @return bool Returns true if the book was successfully updated, false otherwise. 
     */
    public function update(int $idBook, $data): bool
    {
        $book = $this->assemblerBookEntitie($data);

        $book->setId($idBook);

        return $this->bookRepository->updateBook($book);
    }

    /**
     * Retrieves all books.
     * 
     * @return array Returns an array of all books. 
     */
    public function getAll()
    {
        return $this->bookRepository->getAllBooks();
    }

    /**
     * Retrieves a book by its ID.
     * 
     * @param int $idBook The ID of the book to retrieve.
     * @return Book Returns the book if found. 
     */
    public function getBookById(int $idBook): Book
    {
        return $this->bookRepository->getBookById($idBook);
    }

    /**
     * Deletes a book by its ID.
     * 
     * @param int $idBook The ID of the book to delete.
     * @return bool Returns true if the book was successfully deleted, false otherwise. 
     */
    public function delete(int $idBook): bool
    {
        return $this->bookRepository->deleteBook($idBook);
    }

    /**
     * Assembles a Book entity from provided data.
     * 
     * @param object $data The data for the book.
     * @return Book The assembled Book entity. 
     */
    private function assemblerBookEntitie($data)
    {
        $isbn = new ISBN();
        $isbn->setValue($data->isbn);

        $section = $this->sectionService->getSectionById($data->section);

        $book = new Book();
        $book->setTitle($data->title)
            ->setAuthor($data->author)
            ->setAmountOfBooks($data->amountOfBooks)
            ->setISBN($isbn)
            ->setSection($section);

        return $book;

    }
}
