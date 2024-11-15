<?php
namespace App\Library\Application\Controllers;

use App\Library\Application\Utils\Response;
use App\Library\Domain\Exceptions\ResourceNotFoundException;
use App\Library\Domain\Services\BookService;

/**
 * Class BookController
 * 
 * Handles HTTP requests related to books.
 */
class BookController
{
    private BookService $bookService;

    /**
     * Constructor for the BookController class.
     * 
     * Initializes the book service.
     * 
     * @param BookService $bookService The service for managing books.
     */
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Handles the creation of a new book.
     * 
     * @param object $data The data for creating the book.
     * @return void 
     */
    public function create($data)
    {
        $result = $this->bookService->create($data);

        Response::jsonSuccess($result, 201);
    }

    /**
     * Handles the update of an existing book.
     * 
     * @param int $id The ID of the book to be updated.
     * @param object $data The data for updating the book.
     * @return void 
     */
    public function update(int $id, $data)
    {
        $result = $this->bookService->update($id, $data);

        Response::jsonSuccess($result,200);
    }

    /**
     * Handles the retrieval of all books. 
     * @return void 
     */
    public function getAll()
    {
        $result = $this->bookService->getAll();

        Response::jsonSuccess($result, 200);
    }

    /**
     * Handles the retrieval of a book by its ID.
     * 
     * @param int $idBook The ID of the book to retrieve.
     * @return void
     * @throws ResourceNotFoundException If the book is not found. 
     */
    public function getById(int $idBook)
    {
        try {
            $result = $this->bookService->getBookById($idBook);
            Response::jsonSuccess($result, 200);
        } catch (ResourceNotFoundException $e) {
            Response::jsonError($e->getMessage(), 404);
        }
    }

    /**
     * Handles the deletion of a book by its ID.
     * @param int $idLoan The ID of the book to delete.
     * @return void 
     */
    public function delete(int $idLoan)
    {
        $result = $this->bookService->delete($idLoan);

        if (!$result) {
            Response::jsonError("error deleting a book", 400);
        }

        Response::noContent();
    }

}
