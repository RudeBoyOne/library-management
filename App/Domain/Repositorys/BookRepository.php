<?php
namespace App\Library\Domain\Repositorys;

use App\Library\Domain\Entities\Book;

/**
 * 
 * Interface BookRepository 
 * 
 * Defines the operations for managing books. 
 * 
 * */
interface BookRepository
{
    /**
     * Creates a new book.
     * 
     * @param Book $book The book to be created.
     * @return bool Returns true if the book was successfully created, false otherwise. 
     * 
     * */
    public function createBook(Book $book): bool;

    /**
     * Updates an existing book.
     * 
     * @param Book $book The book to be updated.
     * @return bool Returns true if the book was successfully updated, false otherwise. 
     * 
     * */
    public function updateBook(Book $book): bool;

    /**
     * 
     * Retrieves a book by its ID.
     * 
     * @param int $idBook The ID of the book to retrieve.
     * @return Book|null Returns the book if found, null otherwise. 
     * 
     * */
    public function getBookById(int $idBook): ?Book;

    /**
     * 
     * Retrieves all books.
     * 
     * @return array Returns an array of all books. 
     * 
     * */
    public function getAllBooks(): array;

    /**
     * Deletes a book by its ID.
     * 
     * @param int $idBook The ID of the book to delete.
     * @return bool Returns true if the book was successfully deleted, false otherwise. 
     * 
     * */
    public function deleteBook(int $idBook): bool;
}
