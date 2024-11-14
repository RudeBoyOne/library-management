<?php
namespace App\Library\Application\Controllers;

use App\Library\Domain\Exceptions\BookNotAvailableException;
use App\Library\Domain\Exceptions\ExceedsLoanLimitException;
use App\Library\Domain\Exceptions\ResourceNotFoundException;
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
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update($id, $data)
    {
        $result = $this->loanService->update($id, $data);
        echo json_encode($result);
    }

    public function getById(int $idLoan)
    {
        try {
            $result = $this->loanService->getLoanById($idLoan);
            echo json_encode($result);
        } catch (ResourceNotFoundException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    public function getAll()
    {
        $result = $this->loanService->getAllLoans();

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    public function delete(int $idLoan)
    {
        $result = $this->loanService->delete($idLoan);

        echo json_encode($result, JSON_PRETTY_PRINT);
    }
}
