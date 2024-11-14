<?php
namespace Tests\Domain\Services;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\Loan;
use App\Library\Domain\Entities\Role;
use App\Library\Domain\Entities\UserEntities\Professor;
use App\Library\Domain\Entities\UserEntities\Student;
use App\Library\Domain\Repositorys\BookRepository;
use App\Library\Domain\Repositorys\LoanRepository;
use App\Library\Domain\Repositorys\UserRepository;
use App\Library\Domain\Services\LoanService;
use App\Library\Util\DateTimeZoneCustom;
use Tests\SetupTests;

class LoanServiceTest extends SetupTests
{
    private $loanRepositoryMock;
    private $userRepositoryMock;
    private $bookRepositoryMock;
    private $loanServices;
    private $amountOfLoansAllowedForProfessorTypeUser = 5;
    private $amountOfLoansAllowedForStudentTypeUser = 3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loanRepositoryMock = $this->createMock(LoanRepository::class);
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->bookRepositoryMock = $this->createMock(BookRepository::class);

        $this->loanServices = new LoanService($this->loanRepositoryMock);

        $this->insertSampleData();
    }

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
}
