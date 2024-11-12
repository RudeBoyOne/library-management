<?php
namespace Tests\Domain\Repositorys;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\Loan;
use App\Library\Domain\Entities\UserEntities\User;
use App\Library\Domain\Repositorys\LoanRepository;
use App\Library\Infrastructure\Persistence\Connection;
use App\Library\Util\DateTimeZoneCustom;
use PDO;
use PHPUnit\Framework\TestCase;

class LoanRepositoryTest extends TestCase
{
    private PDO $connection;
    private LoanRepository $loanRepository;
    private string $table = 'loan';
    private string $tableAssoc = 'loan_books';

    public function setUp(): void
    {
        $this->connection = new PDO('sqlite::memory:');
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS role (
                id 				INTEGER PRIMARY KEY,
                name 			VARCHAR(100) NOT NULL
            );
        ");

        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS user(
                id	 		    INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                name		    VARCHAR(100) NOT NULL,
                email		    VARCHAR(100) NOT NULL,
                registration	VARCHAR(100) NOT NULL,
                role			INTEGER NOT NULL,
                CONSTRAINT fk_function FOREIGN KEY (role) REFERENCES role(id)
            );
        ");

        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS section(
                id				INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                description		VARCHAR NOT NULL,
                localization	VARCHAR(20) NOT NULL
            );
        ");

        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS book(
                id					INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                title				VARCHAR(100) NOT NULL,
                section	        	INTEGER NOT NULL,
                isbn				VARCHAR(15) NOT NULL,
                author				VARCHAR(100) NOT NULL,
                amount_of_books		INTEGER NOT NULL,
                CONSTRAINT fk_section FOREIGN KEY (section) REFERENCES section(id)
            );
        ");

        $this->connection->exec("
            CREATE TABLE loan(
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                date_loan DATETIME NOT NULL,
                return_loan DATETIME NOT NULL,
                id_user INTEGER NOT NULL,
                CONSTRAINT fk_user FOREIGN KEY (id_user) REFERENCES user(id)
            )
        ");

        $this->connection->exec("
            CREATE TABLE loan_books(
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                id_loan INTEGER NOT NULL,
                id_book INTEGER NOT NULL,
                CONSTRAINT fk_loan_books FOREIGN KEY (id_loan) REFERENCES loan(id),
	            CONSTRAINT fk_book FOREIGN KEY (id_book) REFERENCES book(id)
            )
        ");

        Connection::setInstance($this->connection);

        $this->loanRepository = new LoanRepository();
    }

    public function testCreateLoan(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(1);

        $book1 = $this->createMock(Book::class);
        $book1->method('getId')->willReturn(1);

        $book2 = $this->createMock(Book::class);
        $book2->method('getId')->willReturn(2);

        $loan = $this->createMock(Loan::class);
        $dateLoan = DateTimeZoneCustom::stringToDateTimeConverter('2024-11-15 00:00:00');
        $returnLoan = DateTimeZoneCustom::stringToDateTimeConverter('2024-11-18 00:00:00');

        $loan->method('getDateLoan')->willReturn($dateLoan);
        $loan->method('getReturnLoan')->willReturn($returnLoan);
        $loan->method('getUser')->willReturn($user);
        $loan->method('getBooks')->willReturn([$book1, $book2]);

        $this->assertTrue($this->loanRepository->createLoan($loan));

        $stmt = $this->connection->query("SELECT * FROM $this->table WHERE id_user = 1");
        $loanResult = $stmt->fetch(PDO::FETCH_ASSOC);

        $expectedDateLoan = $dateLoan->format('Y-m-d H:i:s');
        $expectedReturnLoan = $returnLoan->format('Y-m-d H:i:s');

        $this->assertEquals($expectedDateLoan, $loanResult['date_loan']);
        $this->assertEquals($expectedReturnLoan, $loanResult['return_loan']);
        $this->assertEquals(1, $loanResult['id_user']);

        $stmt = $this->connection->query("SELECT * FROM $this->tableAssoc WHERE id_loan = {$loanResult['id']}");
        $loanBooksResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertCount(2, $loanBooksResult);
        $this->assertEquals(1, $loanBooksResult[0]['id_book']);
        $this->assertEquals(2, $loanBooksResult[1]['id_book']);
    }

    public function testUpdateLoan(): void
    {
        $firstUser = $this->createMock(User::class);
        $firstUser->method('getId')->willReturn(1);

        $firstBook1 = $this->createMock(Book::class);
        $firstBook1->method('getId')->willReturn(1);

        $firstBook2 = $this->createMock(Book::class);
        $firstBook2->method('getId')->willReturn(2);

        $firstLoan = $this->createMock(Loan::class);
        $firstDateLoan = DateTimeZoneCustom::stringToDateTimeConverter('2024-11-15 00:00:00');
        $firstReturnLoan = DateTimeZoneCustom::stringToDateTimeConverter('2024-11-18 00:00:00');

        $firstLoan->method('getDateLoan')->willReturn($firstDateLoan);
        $firstLoan->method('getReturnLoan')->willReturn($firstReturnLoan);
        $firstLoan->method('getUser')->willReturn($firstUser);
        $firstLoan->method('getBooks')->willReturn([$firstBook1, $firstBook2]);

        $this->assertTrue($this->loanRepository->createLoan($firstLoan));

        $stmt = $this->connection->query("SELECT * FROM $this->table WHERE id_user = 1");
        $loanResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $idLoan = $loanResult["id"];

        $secondUser = $this->createMock(User::class);
        $secondUser->method('getId')->willReturn(2);

        $secondBook1 = $this->createMock(Book::class);
        $secondBook1->method('getId')->willReturn(3);

        $secondBook2 = $this->createMock(Book::class);
        $secondBook2->method('getId')->willReturn(4);

        $secondLoan = $this->createMock(Loan::class);
        $secondDateLoan = DateTimeZoneCustom::stringToDateTimeConverter('2024-11-25 00:00:00');
        $secondReturnLoan = DateTimeZoneCustom::stringToDateTimeConverter('2024-11-28 00:00:00');

        $secondLoan->method('getId')->willReturn($idLoan);
        $secondLoan->method('getDateLoan')->willReturn($secondDateLoan);
        $secondLoan->method('getReturnLoan')->willReturn($secondReturnLoan);
        $secondLoan->method('getUser')->willReturn($secondUser);
        $secondLoan->method('getBooks')->willReturn([$secondBook1, $secondBook2]);

        $this->assertTrue($this->loanRepository->updateLoan($secondLoan));

        $stmt = $this->connection->query("SELECT * FROM $this->table WHERE id_user = 2");
        $loanResult = $stmt->fetch(PDO::FETCH_ASSOC);

        $expectedDateLoan = $secondDateLoan->format('Y-m-d H:i:s');
        $expectedReturnLoan = $secondReturnLoan->format('Y-m-d H:i:s');

        $this->assertEquals($expectedDateLoan, $loanResult['date_loan']);
        $this->assertEquals($expectedReturnLoan, $loanResult['return_loan']);
        $this->assertEquals(2, $loanResult['id_user']);

        $stmt = $this->connection->query("SELECT * FROM $this->tableAssoc WHERE id_loan = {$loanResult['id']}");
        $loanBooksResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertCount(2, $loanBooksResult);
        $this->assertEquals(3, $loanBooksResult[0]['id_book']);
        $this->assertEquals(4, $loanBooksResult[1]['id_book']);
    }

    public function testGetLoanById(): void
    {
        $this->connection->exec(" INSERT INTO role (name) VALUES ('Professor') ");
        $roleId = $this->connection->lastInsertId();

        $this->connection->exec(" INSERT INTO user (name, email, registration, role) VALUES ('Professor User', 'professor@example.com', '123456', $roleId) ");
        $userId = $this->connection->lastInsertId();

        $this->connection->exec(" INSERT INTO book (title, author, isbn, amount_of_books, section) VALUES ('Book Title 1', 'Author 1', '123-4567890123', 3, 'Section A') ");
        $bookId1 = $this->connection->lastInsertId();
        
        $this->connection->exec(" INSERT INTO book (title, author, isbn, amount_of_books, section) VALUES ('Book Title 2', 'Author 2', '987-6543210987', 5, 'Section B') ");
        $bookId2 = $this->connection->lastInsertId();

        $this->connection->exec(" INSERT INTO loan (date_loan, return_loan, id_user) VALUES ('2024-11-01 10:00:00', '2024-11-15 10:00:00', $userId)");
        $idLoan = $this->connection->lastInsertId();

        $this->connection->exec(" INSERT INTO loan_books (id_loan, id_book) VALUES ($idLoan, $bookId1)");
        $this->connection->exec(" INSERT INTO loan_books (id_loan, id_book) VALUES ($idLoan, $bookId2)");

        $loan = $this->loanRepository->getLoanById($idLoan);
        $this->assertEquals($idLoan, $loan->getId());
        $this->assertEquals('2024-11-01 10:00:00', $loan->getDateLoan()->format('Y-m-d H:i:s'));
        $this->assertEquals('2024-11-15 10:00:00', $loan->getReturnLoan()->format('Y-m-d H:i:s'));
        $this->assertEquals(1, $loan->getUser()->getId());

        $books = $loan->getBooks();
        $this->assertCount(2, $books);
        $this->assertEquals(1, $books[0]->getId());
        $this->assertEquals(2, $books[1]->getId());
    }
}
