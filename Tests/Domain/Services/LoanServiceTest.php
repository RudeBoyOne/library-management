<?php
namespace Tests\Domain\Services;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\ISBN;
use App\Library\Domain\Entities\Loan;
use App\Library\Domain\Entities\Role;
use App\Library\Domain\Entities\Section;
use App\Library\Domain\Entities\UserEntities\Professor;
use App\Library\Domain\Entities\UserEntities\Student;
use App\Library\Domain\Entities\UserEntities\User;
use App\Library\Domain\Repositorys\BookRepository;
use App\Library\Domain\Repositorys\Implementation\BookRepositoryImpl;
use App\Library\Domain\Repositorys\Implementation\SectionRepositoryImpl;
use App\Library\Domain\Repositorys\LoanRepository;
use App\Library\Domain\Repositorys\UserRepository;
use App\Library\Domain\Services\LoanService;
use App\Library\Infrastructure\Persistence\Connection;
use App\Library\Util\DateTimeZoneCustom;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Metadata\Covers;
use Tests\SetupTests;

#[CoversClass(LoanService::class)]
#[CoversClass(Book::class)]
#[CoversClass(ISBN::class)]
#[CoversClass(Loan::class)]
#[CoversClass(Role::class)]
#[CoversClass(Section::class)]
#[CoversClass(Professor::class)]
#[CoversClass(Student::class)]
#[CoversClass(User::class)]
#[CoversClass(BookRepositoryImpl::class)]
#[CoversClass(SectionRepositoryImpl::class)]
#[CoversClass(UserRepository::class)]
#[CoversClass(Connection::class)]
#[CoversClass(DateTimeZoneCustom::class)]
class LoanServiceTest extends SetupTests
{
    private $loanRepositoryMock;
    private $userRepositoryMock;
    private $bookRepositoryMock;
    private $loanServices;
    private $amountOfLoansAllowedForProfessorTypeUser = 5;
    private $amountOfLoansAllowedForStudentTypeUser = 3;

    #[Covers('LoanService::__construct')]
    protected function setUp(): void
    {
        parent::setUp();

        $this->loanRepositoryMock = $this->createMock(LoanRepository::class);
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->bookRepositoryMock = $this->createMock(BookRepository::class);

        $this->loanServices = new LoanService($this->loanRepositoryMock);

        $this->insertSampleData();
    }

    #[Covers('LoanService::create')]
    #[Covers('Role::getName')]
    #[Covers('Student::getRole')]
    #[Covers('Student::getLoanAmount')]
    #[Covers('UserRepository::getUserById')]
    public function testCreateProfessorExceedingLoanLimit()
    {
        $data = (object) ['user' => 1, 'books' => [1, 2], 'dateLoan' => '2024-11-01 10:00:00', 'returnLoan' => '2024-11-15 10:00:00'];

        $role = $this->createMock(Role::class);
        $role->method('getName')->willReturn('Professor');

        $professor = $this->createMock(Professor::class);
        $professor->method('getRole')->willReturn($role);
        $professor->method('getLoanAmount')->willReturn(6);

        $this->userRepositoryMock->method('getUserById')->willReturn($professor);

        $result = $this->loanServices->create($data);
        $this->assertFalse($result);
    }

    #[Covers('LoanService::create')]
    #[Covers('Role::getName')]
    #[Covers('Student::getRole')]
    #[Covers('Student::getLoanAmount')]
    #[Covers('UserRepository::getUserById')]
    public function testCreateStudentExceedingLoanLimit()
    {
        $data = (object) ['user' => 2, 'books' => [1, 2], 'dateLoan' => '2024-11-01 10:00:00', 'returnLoan' => '2024-11-15 10:00:00'];

        $role = $this->createMock(Role::class);
        $role->method('getName')->willReturn('Student');

        $student = $this->createMock(Student::class);
        $student->method('getRole')->willReturn($role);
        $student->method('getLoanAmount')->willReturn($this->amountOfLoansAllowedForStudentTypeUser);

        $this->userRepositoryMock->method('getUserById')->willReturn($student);

        $result = $this->loanServices->create($data);
        $this->assertFalse($result);
    }

    #[Covers('LoanService::create')]
    #[Covers('Book::getId')]
    #[Covers('Book::getTitle')]
    #[Covers('Book::getAmountOfBooks')]
    #[Covers('BookRepositoryImpl::getBookById')]
    #[Covers('LoanRepository::howManyCopiesOfABookAreOnLoan')]
    public function testCreateBookNotAvailable()
    {
        $data = (object) ['user' => 1, 'books' => [1], 'dateLoan' => '2024-11-01 10:00:00', 'returnLoan' => '2024-11-15 10:00:00'];

        $book = $this->createMock(Book::class);
        $book->method('getId')->willReturn(1);
        $book->method('getTitle')->willReturn('Book Title 1');
        $book->method('getAmountOfBooks')->willReturn(1);

        $this->bookRepositoryMock->method('getBookById')->willReturn($book);
        $this->loanRepositoryMock->method('howManyCopiesOfABookAreOnLoan')->with(1)->willReturn(2);

        $this->assertFalse($this->loanServices->create($data));
    }

    #[Covers('LoanService::create')]
    #[Covers('Role::getName')]
    #[Covers('Professor::getRole')]
    #[Covers('Professor::getLoanAmount')]
    #[Covers('Book::getId')]
    #[Covers('Book::getTitle')]
    #[Covers('Book::getAmountOfBooks')]
    #[Covers('UserRepository::getUserById')]
    #[Covers('BookRepositoryImpl::getBookById')]
    #[Covers('LoanRepository::howManyCopiesOfABookAreOnLoan')]
    public function testCreateSuccessful()
    {
        $data = (object) ['user' => 1, 'books' => [1, 2], 'dateLoan' => '2024-11-01 10:00:00', 'returnLoan' => '2024-11-15 10:00:00'];

        $role = $this->createMock(Role::class);
        $role->method('getName')->willReturn('Professor');

        $professor = $this->createMock(Professor::class);
        $professor->method('getRole')->willReturn($role);
        $professor->method('getLoanAmount')->willReturn($this->amountOfLoansAllowedForProfessorTypeUser);

        $book1 = $this->createMock(Book::class);
        $book1->method('getId')->willReturn(1);
        $book1->method('getAmountOfBooks')->willReturn(5);

        $book2 = $this->createMock(Book::class);
        $book2->method('getId')->willReturn(2);
        $book2->method('getAmountOfBooks')->willReturn(5);

        $this->userRepositoryMock->method('getUserById')->willReturn($professor);

        $this->bookRepositoryMock->method('getBookById')->willReturnMap([[1, $book1], [2, $book2]]);

        $this->loanRepositoryMock->method('howManyCopiesOfABookAreOnLoan')->willReturn(1);

        $this->loanRepositoryMock->expects($this->once())
            ->method('createLoan')
            ->with($this->isInstanceOf(Loan::class))
            ->willReturn(true);

        $result = $this->loanServices->create($data);

        $this->assertTrue($result);
    }

    #[Covers('LoanService::update')]
    #[Covers('LoanRepository::updateLoan')]
    #[Covers('UserRepository::getUserById')]
    #[Covers('BookRepository::getBookById')]
    #[Covers('Role::getName')]
    #[Covers('User::getLoanAmount')]
    #[Covers('Loan::setId')]
    public function testUpdate()
    {
        $data = (object) ['user' => 1, 'books' => [1, 2], 'dateLoan' => '2024-11-01 10:00:00', 'returnLoan' => '2024-11-15 10:00:00'];

        $user = $this->createMock(User::class);
        $user->method('getRole')->willReturn($this->createMock(Role::class));
        $user->method('getLoanAmount')->willReturn(1);

        $this->userRepositoryMock->method('getUserById')->willReturn($user);
        $this->loanRepositoryMock->method('howManyLoansDoesAUserHave')->willReturn(1);
        $this->loanRepositoryMock->method('updateLoan')->willReturn(true);

        $result = $this->loanServices->update(1, $data);

        $this->assertTrue($result);
    }

    #[Covers('LoanService::getLoanById')]
    #[Covers('LoanRepository::getLoanById')]
    public function testGetLoanById()
    {
        $loan = $this->createMock(Loan::class);
        $this->loanRepositoryMock->method('getLoanById')->willReturn($loan);
        $result = $this->loanServices->getLoanById(1);
        $this->assertSame($loan, $result);
    }

    #[Covers('LoanService::getAllLoans')]
    #[Covers('LoanRepository::getAllLoans')]
    public function testGetAllLoans()
    {
        $loans = [$this->createMock(Loan::class), $this->createMock(Loan::class)];
        $this->loanRepositoryMock->method('getAllLoans')->willReturn($loans);
        $result = $this->loanServices->getAllLoans();
        $this->assertSame($loans, $result);
    }

    #[Covers('LoanService::delete')]
    #[Covers('LoanRepository::deleteLoan')]
    public function testDelete()
    {
        $this->loanRepositoryMock->method('deleteLoan')
            ->willReturn(true);
        $result = $this->loanServices->delete(1);
        $this->assertTrue($result);
    }
}
