<?php
namespace App\Library\Domain\Repositorys;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\ISBN;
use App\Library\Domain\Entities\Section;
use App\Library\Infrastructure\Persistence\Connection;
use PDO;

class BookRepository
{
    private PDO $connection;
    private string $table = "book";

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function createBook(Book $book): bool
    {
        $title = $book->getTitle();
        $author = $book->getAuthor();
        $isbn = $book->getIsbn()->getValue();
        $amountOfBooks = $book->getAmountOfBooks();
        $section = $book->getSection()->getId();

        $query = "INSERT INTO $this->table(title, author, isbn, amount_of_books, section) VALUES(:title, :author, :isbn, :amount_of_books, :section)";

        $statement = $this->connection->prepare($query);

        $statement->bindParam(":title", $title, PDO::PARAM_STR);
        $statement->bindParam(":author", $author, PDO::PARAM_STR);
        $statement->bindParam(":isbn", $isbn, PDO::PARAM_STR);
        $statement->bindParam(":amount_of_books", $amountOfBooks, PDO::PARAM_INT);
        $statement->bindParam(":section", $section, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function updateBook(Book $book): bool
    {
        $id = $book->getId();
        $title = $book->getTitle();
        $author = $book->getAuthor();
        $isbn = $book->getIsbn()->getValue();
        $amountOfBooks = $book->getAmountOfBooks();
        $section = $book->getSection()->getId();

        $query = "UPDATE $this->table SET title = :title, author = :author, isbn = :isbn, amount_of_books = :amount_of_books, section = :section WHERE id = :id";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->bindParam(":title", $title, PDO::PARAM_STR);
        $statement->bindParam(":author", $author, PDO::PARAM_STR);
        $statement->bindParam(":isbn", $isbn, PDO::PARAM_STR);
        $statement->bindParam(":amount_of_books", $amountOfBooks, PDO::PARAM_INT);
        $statement->bindParam(":section", $section, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function getBookById(int $idBook): ?Book
    {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $idBook, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_OBJ);

        $isbn = new ISBN();
        $isbn->setValue($result->isbn);

        $section = new Section();
        $section->setId((int)$result->section);

        $book = new Book();
        $book->setId((int)$result->id)
            ->setTitle($result->title)
            ->setAuthor($result->author)
            ->setIsbn($isbn)
            ->setAmountOfBooks($result->amount_of_books)
            ->setSection($section);

        return $book;
    }

    public function getAllBooks(): array
    {
        $query = "SELECT * FROM $this->table";
        $statement = $this->connection->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteBook(int $idBook): bool
    {
        $query = "DELETE FROM $this->table WHERE id = :id";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $idBook, PDO::PARAM_INT);

        return $statement->execute();
    }
}
