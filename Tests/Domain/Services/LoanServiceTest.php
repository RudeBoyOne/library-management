<?php
namespace Tests\Domain\Services;

use App\Library\Domain\Entities\Loan;
use App\Library\Domain\Repositorys\LoanRepository;
use App\Library\Domain\Services\LoanService;
use App\Library\Util\DateTimeZoneCustom;
use PHPUnit\Framework\TestCase;

class LoanServiceTest extends TestCase
{
    private $loanRepository;
    private $loanServices;

    protected function setUp(): void
    {
        $this->loanRepository = $this->createMock(LoanRepository::class);
        // Injeção de dependência do mock no serviço
        $this->loanServices = new LoanService($this->loanRepository);
    }

    public function testCreate()
    {
        $data = (object) ['user' => 1, 'book' => [1, 2], 'dateLoan' => DateTimeZoneCustom::stringToDateTimeConverter('2024-11-01 10:00:00'), 'returnLoan' => DateTimeZoneCustom::stringToDateTimeConverter('2024-11-15 10:00:00')];

        $loan = $this->createMock(Loan::class);
        $loan->method('getId')->willReturn(1);
        $loan->method('getDateLoan')->willReturn($data->dateLoan);
        $loan->method('getReturnLoan')->willReturn($data->returnLoan);

        $this->loanRepository->expects($this->once())
                            ->method('createLoan')
                            ->with($this->isInstanceOf(Loan::class))
                            ->willReturn(true);

        $result = $this->loanServices->create($data);

        $this->assertTrue($result);
    }

}
