<?php
namespace Tests\Domain\Entities;

use App\Library\Domain\Entities\Loan;
use App\Library\Domain\Entities\User;
use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LoanTest extends TestCase
{
    public function testGetAndSetDateLoan()
    {
        $dateLoan = new DateTime('2024-11-08');
        $loan = new Loan();
        $loan->setDateLoan($dateLoan);
        $this->assertSame($dateLoan, $loan->getDateLoan());
    }
    
    public function testGetAndSetReturnLoan()
    {
        $dateLoan = new DateTime('2024-11-08');
        $returnLoan = new DateTime('2024-12-08');
        $loan = new Loan();
        $loan->setDateLoan($dateLoan);
        $loan->setReturnLoan($returnLoan);
        $this->assertSame($returnLoan, $loan->getReturnLoan());
    }

    public function testGetAndSetUser()
    {
        $user = $this->createMock(User::class);
        $loan = new Loan();
        $loan->setUser($user);
        $this->assertSame($user, $loan->getUser());
    }

    public function testReturnLoanDateIsAfterDateLoan()
    {
        $dateLoan = new DateTime('2024-11-08');
        $returnLoan = new DateTime('2024-12-08');
        $loan = new Loan();
        $loan->setDateLoan($dateLoan);
        $loan->setReturnLoan($returnLoan);
        $this->assertGreaterThan($loan->getDateLoan(), $loan->getReturnLoan());
    }

    public function testReturnLoanDateBeforeDateLoanThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Return date must be after loan date");
        $dateLoan = new DateTime('2024-11-08');
        $returnLoan = new DateTime('2024-11-07');
        $loan = new Loan();
        $loan->setDateLoan($dateLoan);
        $loan->setReturnLoan($returnLoan);}

    public function testSetFutureReturnLoanDate()
    {
        $dateLoan = new DateTime('2024-11-08');
        $futureReturnLoan = (new DateTime('2024-11-08'))->modify('+30 days');
        $loan = new Loan();
        $loan->setDateLoan($dateLoan);
        $loan->setReturnLoan($futureReturnLoan);
        $this->assertGreaterThan($loan->getDateLoan(), $loan->getReturnLoan());
    }
}
