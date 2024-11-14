<?php
namespace App\Library\Application\Controllers;

use App\Library\Domain\Exceptions\BookNotAvailableException;
use App\Library\Domain\Exceptions\ExceedsLoanLimitException;
use App\Library\Domain\Services\LoanService;

class LoanController
{
    private LoanService $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function create($data)
    {
        try { 
            $result = $this->loanService->create($data);
            echo json_encode($result);
        } catch (BookNotAvailableException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        } catch (ExceedsLoanLimitException $e) {
            echo json_encode(['error'=> $e->getMessage()]);
        }
    }
}
