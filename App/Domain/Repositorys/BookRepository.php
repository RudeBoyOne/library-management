<?php
namespace App\Library\Domain\Repositorys;

use App\Library\Domain\Entities\Book;

interface BookRepository
{
    public function createBook(Book $book): bool;
    public function updateBook(Book $book): bool;
    public function getBookById(int $idBook): ?Book;
    public function getAllBooks(): array;
    public function deleteBook(int $idBook): bool;
}
