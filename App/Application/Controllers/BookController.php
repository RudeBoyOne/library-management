<?php
namespace App\Library\Application\Controllers;

use App\Library\Application\Utils\Response;
use App\Library\Domain\Exceptions\ResourceNotFoundException;
use App\Library\Domain\Services\BookService;

class BookController
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function create($data)
    {
        $result = $this->bookService->create($data);

        Response::jsonSuccess($result, 201);
    }

    public function update(int $id, $data)
    {
        $result = $this->bookService->update($id, $data);

        Response::jsonSuccess($result,200);
    }

    public function getAll()
    {
        $result = $this->bookService->getAll();

        Response::jsonSuccess($result, 200);
    }

    public function getById(int $idBook)
    {
        try {
            $result = $this->bookService->getBookById($idBook);
            Response::jsonSuccess($result, 200);
        } catch (ResourceNotFoundException $e) {
            Response::jsonError($e->getMessage(), 404);
        }
    }

    public function delete(int $idLoan)
    {
        $result = $this->bookService->delete($idLoan);

        if (!$result) {
            Response::jsonError("error deleting a book", 400);
        }

        Response::noContent();
    }

}
