<?php
namespace Tests\Domain\Entities;

use App\Library\Domain\Entities\Loan;
use App\Library\Domain\Entities\UserEntities\User;
use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\Covers;

#[CoversClass(Loan::class)]
#[CoversClass(User::class)]
class LoanTest extends TestCase
{
    #[Covers('Loan::setDateLoan')]
    #[Covers('Loan::getDateLoan')]
    public function testGetAndSetDateLoan()
    {
        $dateLoan = new DateTime('2024-11-08');
        $loan = new Loan();
        $loan->setDateLoan($dateLoan);
        $this->assertSame($dateLoan, $loan->getDateLoan());
    }

    #[Covers('Loan::setReturnLoan')]
    #[Covers('Loan::getReturnLoan')]
    public function testGetAndSetReturnLoan()
    {
        $dateLoan = new DateTime('2024-11-08');
        $returnLoan = new DateTime('2024-12-08');
        $loan = new Loan();
        $loan->setDateLoan($dateLoan);
        $loan->setReturnLoan($returnLoan);
        $this->assertSame($returnLoan, $loan->getReturnLoan());
    }

    #[Covers('Loan::setUser')]
    #[Covers('Loan::getUser')]
    public function testGetAndSetUser()
    {
        $user = $this->createMock(User::class);
        $loan = new Loan();
        $loan->setUser($user);
        $this->assertSame($user, $loan->getUser());
    }

    #[Covers('Loan::setReturnLoan')]
    #[Covers('Loan::getDateLoan')]
    #[Covers('Loan::getReturnLoan')]
    public function testReturnLoanDateIsAfterDateLoan()
    {
        $dateLoan = new DateTime('2024-11-08');
        $returnLoan = new DateTime('2024-12-08');
        $loan = new Loan();
        $loan->setDateLoan($dateLoan);
        $loan->setReturnLoan($returnLoan);
        $this->assertGreaterThan($loan->getDateLoan(), $loan->getReturnLoan());
    }

    #[Covers('Loan::setReturnLoan')]
    #[Covers('Loan::setDateLoan')]
    public function testReturnLoanDateBeforeDateLoanThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Return date must be after loan date");
        $dateLoan = new DateTime('2024-11-08');
        $returnLoan = new DateTime('2024-11-07');
        $loan = new Loan();
        $loan->setDateLoan($dateLoan);
        $loan->setReturnLoan($returnLoan);
    }

    #[Covers('Loan::setReturnLoan')] 
    #[Covers('Loan::getReturnLoan')] 
    #[Covers('Loan::setDateLoan')] 
    #[Covers('Loan::getDateLoan')]
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
