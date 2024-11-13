<?php
namespace Tests\Domain\Services;

use App\Library\Domain\Entities\Loan;
use App\Library\Domain\Repositorys\LoanRepository;
use App\Library\Domain\Services\LoanService;
use App\Library\Util\DateTimeZoneCustom;
use Tests\SetupTests;

class LoanServiceTest extends SetupTests
{
    private $loanRepositoryMock;
    private LoanService $loanServices;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loanRepositoryMock = $this->createMock(LoanRepository::class);
        $this->loanServices = new LoanService($this->loanRepositoryMock);

        $this->insertSampleData();
    }

    public function testCreate()
    {
        $data = (object) ['user' => 1, 'books' => [1, 2], 'dateLoan' => DateTimeZoneCustom::stringToDateTimeConverter('2024-11-01 10:00:00'), 'returnLoan' => DateTimeZoneCustom::stringToDateTimeConverter('2024-11-15 10:00:00')];

        $loan = $this->createMock(Loan::class);
        $loan->method('getId')->willReturn(1);
        $loan->method('getDateLoan')->willReturn($data->dateLoan);
        $loan->method('getReturnLoan')->willReturn($data->returnLoan);

        $this->loanRepositoryMock->expects($this->once())
                            ->method('createLoan')
                            ->with($this->isInstanceOf(Loan::class))
                            ->willReturn(true);

        $result = $this->loanServices->create($data);

        $this->assertTrue($result);
    }

}
