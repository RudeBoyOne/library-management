<?php
namespace App\Library\Domain\Repositorys\Implementation;

use App\Library\Domain\Entities\Loan;
use App\Library\Domain\Exceptions\ResourceNotFoundException;
use App\Library\Domain\Repositorys\BookRepository;
use App\Library\Domain\Repositorys\LoanRepository;
use App\Library\Domain\Repositorys\UserRepository;
use App\Library\Infrastructure\Persistence\Connection;
use App\Library\Util\DateTimeZoneCustom;
use PDO;
use PDOException;

class LoanRepositoryImpl implements LoanRepository
{
    private PDO $connection;
    private string $table = "loan";
    private string $tableAssoc = "loan_books";
    private UserRepository $userRepository;
    private BookRepository $bookRepository;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
        $this->userRepository = new UserRepository();
        $this->bookRepository = new BookRepository();
    }

    public function createLoan(Loan $loan): bool
    {

        $dateLoan = DateTimeZoneCustom::dateTimeToStringConverter($loan->getDateLoan());
        $returnLoan = DateTimeZoneCustom::dateTimeToStringConverter($loan->getReturnLoan());
        $user = $loan->getUser()->getId();
        $books = $loan->getBooks();

        $queryLoan = "INSERT INTO $this->table(date_loan, return_loan, id_user) VALUES(:date_loan, :return_loan, :id_user)";

        try {
            $this->connection->beginTransaction();

            $statement = $this->connection->prepare($queryLoan);
            $statement->bindParam(":date_loan", $dateLoan, PDO::PARAM_STR);
            $statement->bindParam(":return_loan", $returnLoan, PDO::PARAM_STR);
            $statement->bindParam(":id_user", $user, PDO::PARAM_INT);
            $statement->execute();
            $idLoan = $this->connection->lastInsertId();

            $this->loanAndBookAssociationCreator($idLoan, $books);

            return $this->connection->commit();

        } catch (PDOException $e) {

            return $this->connection->rollBack();

        }
    }

    public function updateLoan(Loan $loan): bool
    {
        $idLoan = $loan->getId();
        $dateLoan = DateTimeZoneCustom::dateTimeToStringConverter($loan->getDateLoan());
        $returnLoan = DateTimeZoneCustom::dateTimeToStringConverter($loan->getReturnLoan());
        $user = $loan->getUser()->getId();
        $books = $loan->getBooks();

        $query = "UPDATE $this->table SET date_loan = :date_loan, return_loan = :return_loan, id_user = :id_user";

        try {
            $this->connection->beginTransaction();

            $statement = $this->connection->prepare($query);
            $statement->bindParam(":date_loan", $dateLoan, PDO::PARAM_STR);
            $statement->bindParam(":return_loan", $returnLoan, PDO::PARAM_STR);
            $statement->bindParam(":id_user", $user, PDO::PARAM_INT);
            $statement->execute();

            $this->loanAndBookAssociationDestroyer($idLoan);

            $this->loanAndBookAssociationCreator($idLoan, $books);

            return $this->connection->commit();

        } catch (PDOException $e) {

            return $this->connection->rollBack();

        }
    }

    public function getLoanById(int $idLoan): Loan
    {
        $query = "SELECT * FROM $this->table WHERE id = :id_loan";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id_loan", $idLoan, PDO::PARAM_INT);
        $statement->execute();
        $loan = $statement->fetch(PDO::FETCH_OBJ);

        if (!$loan) {
            throw new ResourceNotFoundException("Empréstimo", $idLoan);
        }
        
        $loanEntitie = $this->assemblerLoanWithUserAndBooks($loan);
        
        return $loanEntitie;

    }

    public function toPickUpBooksFromALoan(int $idLoan): array
    {
        $query = "SELECT * FROM $this->tableAssoc WHERE id_loan = :id_loan";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id_loan", $idLoan, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $booksEntitie = $this->assemblerLoanWithBooks($result);

        return $booksEntitie;
    }

    public function getAllLoans(): array
    {
        $loansEntitiesArray = array();

        $query = "SELECT * FROM $this->table";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $loans = $statement->fetchAll(PDO::FETCH_OBJ);

        foreach ($loans as $loan) {
            $loanEntitie = $this->assemblerLoanWithUserAndBooks($loan);
            array_push($loansEntitiesArray, $loanEntitie);
        }

        return $loansEntitiesArray;
    }

    public function deleteLoan(int $idLoan): bool
    {
        $queryDeleteBooks = "DELETE FROM $this->tableAssoc WHERE id_loan = :id_loan";
        $queryDeleteLoan = "DELETE FROM $this->table WHERE id = :id";

        try {
            $this->connection->beginTransaction();

            $statement = $this->connection->prepare($queryDeleteBooks);
            $statement->bindParam(":id_loan", $idLoan, PDO::PARAM_INT);
            $statement->execute();

            $statement = $this->connection->prepare($queryDeleteLoan);
            $statement->bindParam(":id", $idLoan, PDO::PARAM_INT);
            $statement->execute();

            return $this->connection->commit();
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return false;
        }
    }

    private function loanAndBookAssociationCreator(int $idLoan, array $books)
    {
        $query = "INSERT INTO $this->tableAssoc(id_loan, id_book) VALUES(:id_loan, :id_book)";

        foreach ($books as $book) {
            $idBook = $book->getId();
            $statement = $this->connection->prepare($query);
            $statement->bindParam(":id_loan", $idLoan, PDO::PARAM_INT);
            $statement->bindParam(":id_book", $idBook, PDO::PARAM_INT);
            $statement->execute();
        }
    }

    private function loanAndBookAssociationDestroyer(int $idLoan)
    {
        $query = "DELETE FROM $this->tableAssoc WHERE id_loan = :id_loan";
        $statementDelete = $this->connection->prepare($query);
        $statementDelete->bindParam(":id_loan", $idLoan, PDO::PARAM_INT);
        $statementDelete->execute();
    }

    private function assemblerLoanWithUser(int $idUser)
    {
        $user = $this->userRepository->getUserById($idUser);
        return $user;
    }

    private function assemblerLoanWithBooks(array $resulLoanAssocBook): array
    {
        $books = [];
        foreach ($resulLoanAssocBook as $row) {
            array_push($books, $this->bookRepository->getBookById($row['id_book']));
        }
        return $books;
    }

    private function assemblerLoanWithUserAndBooks($loan): Loan
    {
        $loanEntitie = new Loan();
        $loanEntitie->setId($loan->id)
            ->setDateLoan(DateTimeZoneCustom::stringToDateTimeConverter($loan->date_loan))
            ->setReturnLoan(DateTimeZoneCustom::stringToDateTimeConverter($loan->return_loan))
            ->setUser($this->assemblerLoanWithUser($loan->id_user))
            ->setBooks($this->toPickUpBooksFromALoan($loan->id));

        return $loanEntitie;
    }

    public function howManyCopiesOfABookAreOnLoan(int $idBook): int
    {
        $query = "SELECT COUNT(*) AS count FROM loan_books WHERE id_book = :id_book GROUP BY id_book";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id_book", $idBook, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return 0;
        }

        return (int) $result['count'];
    }

    public function howManyLoansDoesAUserHave(int $idUser): int
    {
        $query = "SELECT COUNT(*) AS count FROM loan WHERE id_user = :id_user GROUP BY id_user";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id_user", $idUser, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return 0;
        }

        return (int) $result['count'];
    }
}
