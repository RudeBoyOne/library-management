<?php
namespace Tests\Domain\Repositorys;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\Loan;
use App\Library\Domain\Entities\UserEntities\User;
use App\Library\Domain\Repositorys\Implementation\LoanRepositoryImpl;
use App\Library\Util\DateTimeZoneCustom;
use PDO;
use Tests\SetupTests;

class LoanRepositoryImplTest extends SetupTests
{
    private LoanRepositoryImpl $loanRepository;
    private string $table = 'loan';
    private string $tableAssoc = 'loan_books';

    public function setUp(): void
    {
        parent::setUp();

        $this->loanRepository = new LoanRepositoryImpl();

        $this->insertSampleData();
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
        $this->connection->exec(" INSERT INTO loan (date_loan, return_loan, id_user) VALUES ('2024-11-01 10:00:00', '2024-11-15 10:00:00', 1)");
        $idLoan = $this->connection->lastInsertId();

        $this->connection->exec(" INSERT INTO loan_books (id_loan, id_book) VALUES ($idLoan, 1)");
        $this->connection->exec(" INSERT INTO loan_books (id_loan, id_book) VALUES ($idLoan, 2)");

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

    public function testDeleteLoan(): void
    {
        $this->connection->exec(" INSERT INTO loan (date_loan, return_loan, id_user) VALUES ('2024-11-01 10:00:00', '2024-11-15 10:00:00', 2) ");
        $idLoan = $this->connection->lastInsertId();

        $this->connection->exec(" INSERT INTO loan_books (id_loan, id_book) VALUES ($idLoan, 1), ($idLoan, 2) ");

        $this->assertTrue($this->loanRepository->deleteLoan($idLoan));
        
        $stmt = $this->connection->query("SELECT * FROM $this->table WHERE id = $idLoan");
        $loanResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertFalse($loanResult);
        $stmt = $this->connection->query("SELECT * FROM $this->tableAssoc WHERE id_loan = $idLoan");
        $loanBooksResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertCount(0, $loanBooksResult);}
}
