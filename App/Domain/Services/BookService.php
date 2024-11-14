<?php
namespace App\Library\Domain\Services;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\ISBN;
use App\Library\Domain\Repositorys\BookRepository;
use App\Library\Domain\Repositorys\Implementation\SectionRepositoryImpl;
use App\Library\Domain\Services\SectionService;

class BookService
{
    private BookRepository $bookRepository;
    private SectionService $sectionService;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;

        $sectionRepository = new SectionRepositoryImpl();
        $this->sectionService = new SectionService($sectionRepository);
    }

    public function create($data): bool
    {
        $book = $this->assemblerBookEntitie($data);
        return $this->bookRepository->createBook($book);
    }

    public function update(int $idBook, $data): bool
    {
        $book = $this->assemblerBookEntitie($data);

        $book->setId($idBook);

        return $this->bookRepository->updateBook($book);
    }

    public function getAll()
    {
        return $this->bookRepository->getAllBooks();
    }

    public function getBookById(int $idBook): Book
    {
        return $this->bookRepository->getBookById($idBook);
    }

    public function delete(int $idBook): bool
    {
        return $this->bookRepository->deleteBook($idBook);
    }

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
