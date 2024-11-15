<?php
namespace App\Library\Domain\Repositorys\Implementation;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\ISBN;
use App\Library\Domain\Exceptions\ResourceNotFoundException;
use App\Library\Domain\Repositorys\BookRepository;
use App\Library\Domain\Repositorys\SectionRepository;
use App\Library\Infrastructure\Persistence\Connection;
use PDO;

/**
 * Class BookRepositoryImpl
 * 
 * Implements the BookRepository interface using a PDO connection. 
 * */
class BookRepositoryImpl implements BookRepository
{
    private PDO $connection;
    private string $table = "book";
    private SectionRepository $sectionRepository;


    /**
     * Constructor for the BookRepositoryImpl class.
     * 
     * Initializes the database connection and section repository. 
     * 
     * */
    public function __construct()
    {
        $this->connection = Connection::getInstance();
        $this->sectionRepository = new SectionRepositoryImpl();
    }

    /**
     * 
     * {@inheritDoc} 
     * */
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
    
    /**
     * 
     * {@inheritDoc} 
     * */
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
    
    /**
     * 
     * {@inheritDoc} 
     * */
    public function getBookById(int $idBook): ?Book
    {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $idBook, PDO::PARAM_INT);
        $statement->execute();
        
        $book = $statement->fetch(PDO::FETCH_OBJ);
        
        if (!$book) {
            throw new ResourceNotFoundException("Book", $idBook);
        }
        
        $bookEntitie = $this->assemblerBookWithSectionAndIsbn($book);
        
        return $bookEntitie;
    }
    
    /**
     * 
     * {@inheritDoc} 
     * */
    public function getAllBooks(): array
    {
        $books = array();
        
        $query = "SELECT * FROM $this->table";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $resultBooks = $statement->fetchAll(PDO::FETCH_OBJ);
        
        foreach ($resultBooks as $book) {
            array_push($books, $this->assemblerBookWithSectionAndIsbn($book));
        }

        return $books;
    }

    /**
     * 
     * {@inheritDoc} 
     * */
    public function deleteBook(int $idBook): bool
    {
        $query = "DELETE FROM $this->table WHERE id = :id";
        
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $idBook, PDO::PARAM_INT);
        
        return $statement->execute();
    }

    /**
     * Assembles a Book entity with its section and ISBN.
     * 
     * @param object $dataBook The database result.
     * @return Book The assembled Book entity. 
     * */
    private function assemblerBookWithSectionAndIsbn($dataBook): Book
    {
        $isbn = new ISBN();
        $isbn->setValue($dataBook->isbn);

        $section = $this->sectionRepository->getSectionById($dataBook->section);

        $bookEntitie = new Book();
        return $bookEntitie->setId($dataBook->id)
            ->setTitle($dataBook->title)
            ->setAuthor($dataBook->author)
            ->setIsbn($isbn)
            ->setAmountOfBooks($dataBook->amount_of_books)
            ->setSection($section);
    }
}
